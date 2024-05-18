<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiReservationsController;
use App\Services\MTNEPaymentService;
use App\Repositories\Interfaces\ApiReservationsRepositoryInterface;
use App\Models\Restaurant;
use App\Models\DeviceToken;
use App\Models\Reservation;
use App\Notification;

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
            $paymentResponseJson = $this->mtnPaymentService->createInvoice($request->amount, now()->timestamp, $request['customer_phone'], 'Reservation Process');
            $paymentResponseData = json_decode($paymentResponseJson->getContent(), true);

            // Assuming $responseData['reservation_details'] contains reservation details including the ID.
            // This assumes $responseData is previously defined. You might need to adjust this part.
            $reservationDetails = $responseData['reservation_details'];

            // Check the status from the payment response data


            if (isset ($paymentResponseData['status']) && !$paymentResponseData['status']) {
                // If status is false, delete the reservation and return the payment response
                $reservation = Reservation::with('table')->findOrFail($reservationDetails['id']);
                $reservation->update([
                    'status' => 'scheduled',
                    'reservation_date' => $request->date,
                    'reservation_time' => $request->time
                ]);

                // Fetch the reservation along with its related table

                // Assuming $request has the new data for the table
                // Update the table associated with this reservation
                if ($reservation->table) {
                    $reservation->table->update([
                        'size' => $request->size,
                        'location' => $request->location,
                        'type' => $request->type
                        // Add more attributes to update as needed
                    ]);
                }

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
                        'invoice_id' => $paymentResponseData['invoice_id'],
                        'mtn_invoice_id' => $invoiceId
                    ]);
                    return response()->json([
                        'paymentResponse' => $paymentResponseData,
                        'reservationDetails' => $reservationDetails
                    ]);
                }
            }
        } else {
            $reservation = Reservation::with('table')->findOrFail($reservationDetails['id']);
            $reservation->update([
                'status' => 'scheduled',
                'reservation_date' => $request->date,
                'reservation_time' => $request->time
            ]);
            // Fetch the reservation along with its related table

            // Assuming $request has the new data for the table
            // Update the table associated with this reservation
            if ($reservation->table) {
                $reservation->table->update([
                    'size' => $request->size,
                    'location' => $request->location,
                    'type' => $request->type
                    // Add more attributes to update as needed
                ]);
            }

            return response()->json([
                'status' => false,
                'Message' => 'Error in create the invoice'
            ], 401);
        }

    }

    public function resendCode(Request $request, $reservation_id)
    {
        $response = $this->apiReservationsController->reversation_details($reservation_id);

        $responseData = json_decode($response->getContent(), true);

        if ($responseData['status'] === true) {
            //$restaurant = Restaurant::where('name',$responseData['restaurant_name'])->first();
            //$paymentResponseJson = $this->mtnPaymentService->createInvoice($restaurant->Deposite_value, now()->timestamp, $request['customer_phone'], 'Reservation Process');
            $paymentResponseJson = $this->mtnPaymentService->createInvoice($request->amount, now()->timestamp, $request['customer_phone'], 'Reservation Process');
            $paymentResponseData = json_decode($paymentResponseJson->getContent(), true);

            // Assuming $responseData['reservation_details'] contains reservation details including the ID.
            // This assumes $responseData is previously defined. You might need to adjust this part.
            $reservationDetails = $responseData['reservation_details'];

            // Check the status from the payment response data


            if (isset ($paymentResponseData['status']) && !$paymentResponseData['status']) {
                // If status is false, delete the reservation and return the payment response
                //  $reservation = Reservation::with('table')->findOrFail($reservationDetails['id']);
                // $reservation->update([
                ///    'status' => 'scheduled',
                //    'reservation_date' => $request->date,
                //    'reservation_time' => $request->time
                // ]);

                // Fetch the reservation along with its related table

                // Assuming $request has the new data for the table
                // Update the table associated with this reservation
                //if ($reservation->table) {
                //    $reservation->table->update([
                //        'size' => $request->size,
                //       'location' => $request->location,
                //       'type' => $request->type
                // Add more attributes to update as needed
                //]);
                //}

                // Since $paymentResponseJson is already a JsonResponse, you can return it directly or modify as needed
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
            //$reservation = Reservation::with('table')->findOrFail($reservationDetails['id']);
            //$reservation->update([
            //    'status' => 'scheduled',
            //    'reservation_date' => $request->date,
            //    'reservation_time' => $request->time
            //]);
            // Fetch the reservation along with its related table

            // Assuming $request has the new data for the table
            // Update the table associated with this reservation
            //if ($reservation->table) {
            //   $reservation->table->update([
            //       'size' => $request->size,
            //       'location' => $request->location,
            //       'type' => $request->type
            // Add more attributes to update as needed
            //   ]);
            // }

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

            //$this->updateStatus($request) ;

            //broadcast(new NotificationEvent('Reservation Alert', 'Reservation Successfully in Restaurant', $request->device_token))->toOthers();
            //$customer = Auth::user();
            // $customer_device_tokens = DeviceToken::where('customer_id', Auth::id())->get();
            //foreach ($customer_device_tokens as $token) {
            //Notification::sendPushNotification('Reservation Alert', 'Reservation Successfully', $token);
            //}


            return response()->json([
                'status' => true,
                'data' => $paymentResponse
            ]);
            ;
        } else {
            $reservation->update([
                'status' => 'scheduled'
            ]);
            return response()->json([
                'status' => false,
                'Message' => 'Error in create the invoice'
            ], 402);
        }
    }

    public function updateStatus(Request $request)
    {
        $ids = $request->input('ids');

        $request->validate([
            'ids' => 'required|array',
        ]);

        foreach ($ids as $id) {
            $book = Booking::withoutGlobalScopes()->find($id);
            if ($book) {
                $book->update(['status' => 'paid']);
            }
        }

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function cancelReservation(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $paymentResponse = $this->mtnPaymentService->initiateRefund($reservation->mtn_invoice_id);

        DB::beginTransaction();
        if ($paymentResponse[0] === true) {
            $cancelReservationResponse = $this->apiReservationsController->reversation_cancel($id);
            if ($cancelReservationResponse['status'] === true) {
                $confirmResult = $this->mtnPaymentService->confirmRefund($paymentResponse[2], $paymentResponse[1]);
                if ($confirmResult) {
                    DB::commit();
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
                    'message' => 'something wrong in cancel Reservation'
                ]);
            }
        } else {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'something wrong in Initial refund',
                'error' => $paymentResponse[1]
            ]);
        }

    }
}
