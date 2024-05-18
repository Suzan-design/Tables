<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiReservationsController;
use App\Services\MTNEPaymentService;
use App\Repositories\Interfaces\ApiReservationsRepositoryInterface;
use App\Models\Restaurant;
use App\Models\CancelInvoice;
use App\Models\DeviceToken;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Http\Controllers\Api\NotificationsController;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $mtnPaymentService;
    protected $apiReservationsController;

    public function __construct(MTNEPaymentService $mtnPaymentService, ApiReservationsController $apiReservationsController)
    {
        $this->mtnPaymentService = $mtnPaymentService;
        $this->apiReservationsController = $apiReservationsController;
    }

    public function terminalActivation()
    {
        $this->mtnPaymentService->terminalActivation();
    }

    public function createInvoice(Request $request, $restaurant_id)
    {
        $response = $this->apiReservationsController->reversation($request, $restaurant_id);

        $responseData = json_decode($response->getContent(), true);


        if ($responseData['status'] === true) {
            $restaurant = Restaurant::findOrFail($restaurant_id);
            //$paymentResponseJson = $this->mtnPaymentService->createInvoice($restaurant->Deposite_value, now()->timestamp, $request['customer_phone'], 'Reservation Process');
            $amountToCharge = isset($responseData['discounted_value']) ? $responseData['discounted_value'] : $restaurant->Deposite_value;
            
            $paymentResponseJson = $this->mtnPaymentService->createInvoice(((int) $amountToCharge + $restaurant->taxes), now()->timestamp, $request['customer_phone'], 'Reservation Process');
            $paymentResponseData = json_decode($paymentResponseJson->getContent(), true);

            // Assuming $responseData['reservation_details'] contains reservation details including the ID.
            // This assumes $responseData is previously defined. You might need to adjust this part.
            $reservationDetails = $responseData['reservation_details'];

            // Check the status from the payment response data


            if (isset($paymentResponseData['status']) && !$paymentResponseData['status']) {
                // If status is false, delete the reservation and return the payment response
                $reservation = Reservation::with('table')->findOrFail($reservationDetails['id']);
                $reservation->update([
                    'status' => 'scheduled',
                    'reservation_date' => $request->date,
                    'reservation_time' => $request->time
                ]);


                // Since $paymentResponseJson is already a JsonResponse, you can return it directly or modify as needed
                return response()->json([
                    'paymentResponse' => $paymentResponseData,
                    'message' => 'Reservation deleted due to payment failure.'
                ], 400);
            } else {
                if ($responseData['status']) {
                    // If status is true, return both payment response and reservation details
                    $invoiceId = $paymentResponseData['message']['Receipt']['Invoice'];
                    $reservation = Reservation::findOrFail($reservationDetails['id']);
                    $reservation->update([
                        'Restaurant_id'=>$restaurant_id,
                        'invoice_id' => $paymentResponseData['invoice_id'],
                        'mtn_invoice_id' => $invoiceId
                    ]);
                    return response()->json([
                        'paymentResponse' => $paymentResponseData,
                        'reservationDetails' => $reservationDetails
                    ]);
                }
            }
        } elseif (isset($responseData['message']) && str_contains($responseData['message'], 'Promo code is not valid') !== false) {
            return response()->json([
                'status' => false,
                'Message' => 'Promo code is not valid'
            ], 405);
        } else {

            return response()->json([
                'status' => false,
                'Message' => 'no reservation'
            ], 405);
        }

    }

    public function resendCode(Request $request, $reservation_id)
    {
        $response = $this->apiReservationsController->reversation_details($reservation_id);

        $responseData = json_decode($response->getContent(), true);

        if ($responseData['status'] === true) {


            $paymentResponseJson = $this->mtnPaymentService->createInvoice((int) $request->amount, now()->timestamp, $request['customer_phone'], 'Reservation Process');
            $paymentResponseData = json_decode($paymentResponseJson->getContent(), true);

            // Assuming $responseData['reservation_details'] contains reservation details including the ID.
            // This assumes $responseData is previously defined. You might need to adjust this part.
            $reservationDetails = $responseData['reservation_details'];

            // Check the status from the payment response data


            if (isset($paymentResponseData['status']) && !$paymentResponseData['status']) {

                return response()->json([
                    'paymentResponse' => $paymentResponseData,
                    'message' => 'Reservation deleted due to payment failure.'
                ], 400);
            } else {
                // If status is true, return both payment response and reservation details
                $invoiceId = $paymentResponseData['message']['Receipt']['Invoice'];
                $reservation = Reservation::findOrFail($reservation_id);
                $reservation->update([
                    'invoice_id' => $paymentResponseData['invoice_id'],
                    'mtn_invoice_id' => $invoiceId
                ]);
                return response()->json([
                    'paymentResponse' => $paymentResponseData,
                    'reservationDetails' => $reservationDetails
                ]);
            }
        } else {

            return response()->json([
                'status' => false,
                'Message' => 'Error in create the invoice'
            ], 401);
        }
    }

    public function confirmPayment(Request $request, $reservation_id)
    {
        //invoice id is number .. phone_number is string .. code is string
        $reservation = Reservation::findOrFail($reservation_id);


        $paymentResponse = $this->mtnPaymentService->confirmPayment($reservation->invoice_id, $reservation->mtn_invoice_id, $request['customer_phone'], $request['code']);
        $jsonData = json_decode($paymentResponse->getContent(), true);

        if ($jsonData['status'] === true) {

            $reservation->update([
                'status' => 'pending',
                'request_reservation_time' => Carbon::now()
            ]);
            try {
                $today = Carbon::today();
                Notification::create([
                    'title' => 'Request',
                    'ar_title' => 'طلب حجز',
                    'description' => 'Your request has been registered, please wait for the request to be accepted',
                    'ar_description' => 'تم تسجيل طلبك, انتظر حتى يتم قبول الطلب من فضلك',
                    'customer_id' => Auth::id(),
                    'date' => $today
                ]);
                $notificationController = new NotificationsController();
                $notificationController->sentNotification(Auth::id(), 'Request', 'Your request has been registered, please wait for the request to be accepted','طلب حجز','تم تسجيل طلبك,انتظر حتى يتم قبول الطلب من فضلك');
            } catch (\Exception $e) {

            }


            return response()->json([
                'status' => true,
                'data' => $paymentResponse
            ]);
        } else {
            $reservation->update([
                'status' => 'scheduled'
            ]);
            
            return response()->json([
                'status' => false,
                'Message' => $paymentResponse
            ], 402);
        }
    }

    // public function updateStatus(Request $request)
    // {
    //     $ids = $request->input('ids');

    //     $request->validate([
    //         'ids' => 'required|array',
    //     ]);

    //     foreach ($ids as $id) {
    //         $book = Booking::withoutGlobalScopes()->find($id);
    //         if ($book) {
    //             $book->update(['status' => 'paid']);
    //         }
    //     }

    //     return response()->json(['message' => 'Status updated successfully']);
    // }

    public function cancelReservation(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $time =  $reservation->request_reservation_time;
        $restaurant_id =  $reservation->Restaurant_id;
        $restaurant = Restaurant::findOrFail($restaurant_id);
        $cancellition_policy = explode(',', $restaurant->cancellition_policy ?? '');
        $cancellitionValue = trim($cancellition_policy[0] ?? '', '()');
        $updatedAtCarbon = Carbon::parse($time);
        $futureTime = $updatedAtCarbon->addHours($cancellitionValue);
        
        $comparisonTime = Carbon::now();
        if($comparisonTime->lessThan($futureTime)){
              $paymentResponse = $this->mtnPaymentService->initiateRefund($reservation->mtn_invoice_id);
      
              DB::beginTransaction();
              if ($paymentResponse[0] === true) {
                  $cancelReservationResponse = $this->apiReservationsController->reversation_cancel($id);
                  if ($cancelReservationResponse['status'] === true) {
                      $confirmResult = $this->mtnPaymentService->confirmRefund($paymentResponse[2], $paymentResponse[1]);
                      if ($confirmResult) {
                          DB::commit();
                          CancelInvoice::create([
                              'invoice_id' => $reservation->invoice_id
                          ]);
                          $reservation->update([
                              'status' => 'cancelled'
                          ]);
                          return response()->json([
                              'status' => true,
                              'message' => 'Refund Successfully'
                          ]);
                      } else {
                          DB::rollBack();
                          return response()->json([
                              'status' => false,
                              'message' => 'something wrong in confirm refund'
                          ]);
                      }
                  } else {
                      DB::rollBack();
                      return response()->json([
                          'status' => false,
                          'message' => 'Reservation not found'
                      ],202);
                  } 
              } else {
                  DB::rollBack();
                  return response()->json([
                      'status' => false,
                      'message' => 'something wrong in Initial refund',
                      'error' => $paymentResponse[1]
                  ],204);
              }
      
          }else{
          DB::rollBack();
                  return response()->json([
                      'status' => false,
                      'message' => 'you cannot cancel reservation',
                  ],203);
          
          }
          }
}

