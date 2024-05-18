<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\activatetokens;
use Illuminate\Support\Facades\DB;
use App\Services\InvitationService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\ApiAuthRepositoryInterface;
use App\Models\User\ResetCodePassword;
use App\Models\DeviceToken;
use App\Models\Notification;
use App\Http\Controllers\Api\NotificationsController;


class ApiAuthController extends BaseController
{
    protected $invitationService;
    protected $ApiAuthRepository;
    public function __construct(InvitationService $invitationService, ApiAuthRepositoryInterface $ApiAuthRepository)
    {
        $this->invitationService = $invitationService;
        $this->ApiAuthRepository = $ApiAuthRepository;
    }
    public function login(Request $request)
    {
    $rules = [
        'phone' => ['required', 'numeric', 'digits:12', 'exists:customers,phone', 'regex:/^963[0-9]{9}$/'],
        'password' => 'required',
        'device_token' => 'required'
        ];


            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $statusCode = 400; // Default status code for other validation errors
            
                // Check for phone number specific errors
                if ($errors->has('phone')) {
                    foreach ($errors->get('phone') as $error) {
                        if (str_contains($error, 'field format')) {
                            $statusCode = 403; // Set status code for 'unique' rule failure
                            $message = "The phone field format is invalid.";
                            break; // No need to check other phone errors once this is found
                        } elseif (str_contains($error, '12 digits')) {
                            $statusCode = 402; // Set status code for 'digits:12' rule failure
                            $message = "The phone field must be 12 digits.";
                        } elseif (str_contains($error, 'phone is invalid')) {
                            $statusCode = 401; // Set status code for 'digits:12' rule failure
                            $message = "incorrect phone or password";
                        }
                    }
                    return response()->json([
                    'message' => $message,
                    'status' => false
                ], $statusCode);
                }else{
                return response()->json([
                    'message' => $validator->errors(),
                    'status' => false
                ], $statusCode);
                }
            }
                
        try {
        
                
            DB::beginTransaction();

            $customer = Customer::where('phone', $request->phone)->first();
            if ($customer === null || $customer->count() == 0) {
            
                DB::commit();
                return response()->json(
                    [
                        'message' => 'Customer not found or empty',
                        'status' => false
                    ],
                    401
                );
            } else {
                if (!$customer->isBlocked){
                    if ($customer->isVerified == "false" || $customer->is_complete == "false") {
                        $otp = rand(000000, 111111);
                        $customer->update(['otp' => $otp]);
                        DB::commit();
                        return response()->json(
                            [
                                'message' => 'register not completed - verify number',
                                'status' => false,
                                'data' => $customer,
                                'token' => $otp
                            ],
                            205
                        );
                    }
                    }else {
                      DB::commit();
                      return response()->json(
                          [
                              'message' => 'You are blocked',
                              'status' => false
                          ],
                          309
                      );
                    }
            }
            
                $customer = Customer::where('phone', $request->phone)->first();
                if (!$customer || !Hash::check($request->password, $customer->password)) {
                    DB::commit();
                    return response([
                        'msg' => 'incorrect phone or password'
                    ], 401);
                }
                if (!$customer->isBlocked){
                    $token = $customer->createToken('API Token')->accessToken;
                    $res = [
                        'user' => $customer,
                        'token' => $token
                    ];
                    
                    $device_token = $request->device_token;
                    DeviceToken::where('device_token', $device_token)->delete();
        
                // Then, create a new record for the device token
                DeviceToken::create([
                    'customer_id' => $customer->id,
                    'device_token' => $device_token
                ]);
                
                    DB::commit();
                    return response()->json(
                        [
                            'message' => 'sucssefull',
                            'status' => true,
                            'data' => $customer,
                            'token' => $token
                        ],
                        200
                    );
                    }else {
                        DB::commit();
                        return response()->json(
                            [
                                'message' => 'You are blocked',
                                'status' => false
                            ],
                            309
                        );
                    }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => false,
            ], 500);
        }
    
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::where('phone', $request->phone)->first();
            if ($customer) {
                if ($customer->isVerified == "0" || $customer->isComplete == "0") {
                    $otp = rand(000000, 111111);
                    $this->ApiAuthRepository->updateOtp($customer, $otp);
                    DB::commit();
                    return response()->json(
                        [
                            'message' => 'register not completed - verify number',
                            'status' => false,
                            'token' => $otp
                        ],
                        205
                    );
                }else{
                    return response()->json(
                        [
                            'status' => false
                        ],
                        401
                    );
                }
            }
            $data = $request->all();
            $otp = rand(000000, 111111);
            $rules = [
                        'firstname' => 'required|min:3|max:20',
                        'lastname' => 'required|min:3|max:20',
                        'phone' => ['required', 'numeric', 'digits:12', 'unique:customers,phone', 'regex:/^963[0-9]{9}$/'],
                        'email' => 'nullable|email|unique:customers,email',
                      ];


            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $statusCode = 400; // Default status code for other validation errors
            
                // Check for phone number specific errors
                if ($errors->has('phone')) {
                    foreach ($errors->get('phone') as $error) {
                        if (str_contains($error, 'field format')) {
                            $statusCode = 403; // Set status code for 'unique' rule failure
                            $message = "The phone field format is invalid.";
                            break; // No need to check other phone errors once this is found
                        } elseif (str_contains($error, '12 digits')) {
                            $statusCode = 402; // Set status code for 'digits:12' rule failure
                            $message = "The phone field must be 12 digits.";
                            break; // Note: Don't break here if 'unique' rule check is also desired for the same input
                        } elseif (str_contains($error, 'unique')) {
                            $statusCode = 401;
                            $message = "Phone number is already been taken";
                        }
                    }
                }
            
                return response()->json([
                    'message' => $message,
                    'status' => false
                ], $statusCode);
            }

            $customer = Customer::create([
                'firstname' => $data['firstname'],  //min:3 max:20  req
                'lastname' => $data['lastname'],  //min:3 max:20  req
                'phone' => $data['phone'],  ////09 : 8numbers numeric  unique req
                'email' => $data['email'] ?? null,  //email unique
                'otp' => $otp
            ]);

            $customer = $customer->only([
                'id', 'firstname', 'lastname', 'email', 'phone', 'otp'
            ]);
            DB::commit();
            return response()->json(
                [
                    'message' => 'Code Send',
                    'status' => true,
                    'data' => $customer,
                    'otp' => $otp
                ],
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error');
        }
    }
    public function verify(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $customer = $this->ApiAuthRepository->findByPhone($data['phone_number']);
            $customer->update(['isVerified' => true, 'otp' => '-']);
            $token = $customer->createToken('API Token')->accessToken;
            $customer = $customer->only([
                'id', 'firstname', 'lastname', 'email', 'phone'
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Successfull',
                'status' => true,
                'data' => $customer,
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'error',
                'status' => false
            ], 400);
        }
    }
    public function register_complete(Request $request)
    {
   
        $customer = Auth::guard('customer-api')->user();
        $rules = [
            'password' => 'required|confirmed|min:8|regex:/[a-zA-Z0-9@!#�$%^&*()_+{}":;\'?\/\\.,`~]+/',
            'gender' => 'required|in:male,female',
            'State' => 'required',
         //   'profilePicture' => 'nullable|image',
            'birthDate' => 'required',
            'invitationCode' => 'exists:customers,invitationCode',
            'device_token' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
            ], 403);
        }
        try {
        $birthDate = $request->birthDate; // e.g., "1990-01-01"
        
        $dateObject = new \DateTime($birthDate);
        
        $formattedDate = $dateObject->format('Y-m-d');
            DB::beginTransaction();
            $randomString = $this->ApiAuthRepository->generateUniqueCode();
            $customer->update([
                'password' => bcrypt($request->password),
                'allowNotification' => $request->allowNotification,
                'gender' => $request->gender,
                'isComplete' => '1',
                'State' => $request->State,
                'birthDate' => $formattedDate,
                'invitationCode' => $randomString
            ]);
           
            if ($request->hasFile('profilePicture')) {
                  $file = $request->file('profilePicture');
                  $extension = $file->getClientOriginalExtension();
                  $randomName = Str::random(10); // ????? ??? ?????? ?????
                  $name = $randomName . '.' . $extension;
                  $directoryPath = 'public/users/'; // ???? ??? ?????? ??? ???? storage/app/public
                  $filePath = $file->storeAs($directoryPath, $name); // ??? ??????
          
                  $filePath = Storage::url($filePath); // ?????? ??? ??? URL ????? ??????
                    $customer->update(['profilePicture' => $filePath]);
                }
            if ($request->invitationCode) {
                $sender = Customer::where('invitationCode', $request->invitationCode)->first();
                $userOfInvitations = json_decode($sender->userOfInvitations, true) ?? [];
                $userOfInvitations[] = $customer->id;
                $sender->update([
                    'numberOfInvitations' => $sender->numberOfInvitations += 1,
                    'userOfInvitations' => json_encode($userOfInvitations),
                ]);
                $invitation = Invitation::where([
                    'target' => $sender->numberOfInvitations,
                    'type' => 'invitations',
                ])->first();
                if ($invitation) {
                    $this->invitationService->generate_promocode_invitation($invitation, $sender);
                }
                $this->invitationService->generate_promocode_new($customer);
            }
            $customer = $customer->only([
                'id', 'firstname', 'lastname', 'email', 'phone', 'invitationCode', 'gender', 'State', 'allowNotification', 'profilePicture', 'birthDate'
            ]);
            
            DeviceToken::where('device_token', $request->device_token)->delete();

        // Then, create a new record for the device token
        DeviceToken::create([
            'customer_id' => Auth::id(),
            'device_token' => $request->device_token
        ]);
        
                $today = Carbon::today();
                Notification::create([
                    'title' => 'Register',
                    'description' => 'You are registered successfully',
                    'ar_title' => 'إنشاء حساب',
                    'ar_description' => 'تم إنشاء الحساب بنجاح',
                    'customer_id' => Auth::id(),
                    'date' => $today
                ]);
                $notificationController = new NotificationsController();
                $notificationController->sentNotification(Auth::id(), 'Registered Complete Successfully', 'You are registered successfully','إنشاء حساب','تم إنشاء الحساب بنجاح');
        
            DB::commit(); 
            return response()->json([
                'message' => 'Successful',
                'status' => true,
                'data' => $customer,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed',
                'status' => false,
                'data' => $e->getmessage(),
            ], 400);
        }
    }
    public function profile()
    {
        $customer = Auth::guard('customer-api')->user();
        $filteredData = $customer->only([
            'id', 'firstname', 'lastname', 'email', 'phone', 'gender',
            'State', 'profilePicture', 'birthDate'
        ]);
        return $this->sendResponse($filteredData, 'profile');
    }
    
    public function edit_profile(Request $request)
    {

        $customer = Customer::where('id', Auth::guard('customer-api')->id())->first();
        $rules = [
            'firstname' => 'nullable|min:3|max:20',
            'lastname' => 'nullable|min:3|max:20',
            'email' => 'nullable|email|unique:customers,phone,' . $customer->id,
            'state' => 'nullable',
            'birthDate' => 'nullable',
            'gender' => 'nullable',
            'profilePicture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        
   if ($request->hasFile('profilePicture')) {
    $file = $request->file('profilePicture');
   
    $name = time() . '.' . $file->getClientOriginalExtension();
  
    $directoryPath = 'public/users/'; // ???? ??? ?????? ??? ???? storage/app/public
        $filePath = $file->storeAs($directoryPath, $name); // ??? ??????

        $filePath = Storage::url($filePath); // ?????? ??? ??? URL ????? ??????
  
    $customer->update(['profilePicture' => $filePath]);
}
        if($request->email !=null){
          $customer->update([
            'email' => $request->email
        ]);
        }
        $birthDate = $request->birthDate; // e.g., "1990-01-01"
        
        $dateObject = new \DateTime($birthDate);
        
        $formattedDate = $dateObject->format('Y-m-d');

        $customer->update([
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'State' => $request->state,
        'gender' => $request->gender,
        'birthDate' => $formattedDate,
        ]);

        $customer = $customer->only([
            'id', 'firstname', 'lastname', 'email', 'gender',
            'State', 'profilePicture', 'birthDate'
        ]);
        return $this->sendResponse($customer, 'Profile updated successfully');
    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'oldpassword' => 'required',
                'newpassword' => 'required',
                'c_newpassword' => 'required|same:newpassword'
            ]
        );
        if ($validator->fails()) {
            // Handle validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $customer = Auth::guard('customer-api')->user();
        if (Hash::check($request->oldpassword, $customer->password)) {
            $customer->password = bcrypt($request->newpassword);
            $customer->save();
            return $this->sendResponse($customer, 'Password reset successfully!');
        }
        return response()->json(['message' => 'Old password is incorrect'], 401);
    }
    public function sendVerify_password(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'phone' => 'required|exists:customers,phone',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $customer = $this->ApiAuthRepository->findByPhone($request->phone);
        if ($customer) {
            $code = random_int(1000, 9999);
            $customer->update([
                'otp' => $code
            ]);
            return $this->sendResponse($code, 'send code');
        } else {
            return $this->sendError(' Error', ['error', 'Unauthorized']);
        }
    }
    public function new_password(Request $request)
    {
        $customer = $this->ApiAuthRepository->findByPhone($request->phone);
        $validator = Validator::make(
            $request->all(),
            [
                'newpassword' => 'required|min:8|max:60',
                'c_newpassword' => 'required|same:password',
            ]
        );
        $customer->password = bcrypt($request->newpassword);
        $customer->save();
        $token = $customer->createToken('API Token')->accessToken;
        return response()->json([
            'message' => 'Successfull update_password',
            'status' => true,
            'data' => $customer,
            'token' => $token
        ], 200);
    }
    
    public function forgot_password(Request $request){

        $customer = Customer::where('phone', $request->phone)->first();
            if ($customer) {
                  if (!$customer->isBlocked){
                      if ($customer->isVerified == "1" && $customer->isComplete == "1") {
                          $otp = rand(10000, 99999);
                          //Create a new code
                          $CodeData=ResetCodePassword::create([
                              'code' => $otp ,
                              'phone_number' => $request['phone']
                          ]);
                          $this->ApiAuthRepository->updateOtp($customer, $otp);
                          DB::commit();
                          return response()->json(
                      [
                          'message' => 'Code Send',
                          'status' => true,
                          'code' => $otp
                      ],
                  );
                      }else{
                      return response()->json([
                            'status'=>false,
                            'message'=> 'register not completed - verify number'
                        ],401);
              }
              }else{
                    return response()->json([
                      'status'=>false,
                      'message'=> 'You are blocked'
                  ],402);
                  }
            }
            else{
                return response()->json([
            'status'=>false,
            'message'=> 'customer not found'
        ],401);
        }

        
    }

    public function check_code(Request $request)
    {
        //find the code
        $PasswordReset=ResetCodePassword::query()->where('code',$request['code'])->where('phone_number' , $request['phone'])->first();

        if(! $PasswordReset)
        {
            return response()->json([
                'status' =>   false,
                'message' => 'invalid code or invalid number'
                ],401);
        }
        //check if it is not expired:the time is one hour
        if($PasswordReset['created_at'] > now()->addHour())
        {
            $PasswordReset->delete();
            return response()->json(['status' => false , 'message'=>trans('password.code_is_expire')],400);
        }
        return response()->json([
            'status'=>true,
            'code' => $PasswordReset['code'],
            'message' => trans('password.code_is_valid')
        ]);
        
    }

    public function change_password(Request $request){

       $input = $request->all();
        //find the code
        $PasswordReset=ResetCodePassword::query()->where('code',$request['code'])->where('phone_number' , $request['phone'])->first();
        
        if(! $PasswordReset)
        {
            return response()->json([
                'status'  => false ,
                'message' => 'invalid code'
            ],500);
        }
        //check if it is not expired:the time is one hour
        if($PasswordReset['created_at'] > now()->addHour() )
        {
            $PasswordReset->delete();
            return response()->json([ 'status' => false, 'message'=>trans('password code is expire')],);
        }
        //find users email
        $customer = Customer::query()->firstWhere('phone',$PasswordReset['phone_number']);
        //update user password
        $input['password'] = bcrypt($input['password']);
        $customer->update(['password' => $input['password']]);
        //delete current code
        $PasswordReset->delete();

        return response()->json([
            'status'=>true,
            'message' => 'password has been successfully reset']);
    }
    
    //delete user account
    public function delete_account(Request $request){
    try {
        $customer = Auth::guard('customer-api')->user();

        if ($customer) {
            $customer->delete();
            
            // Assuming you're using Laravel Passport
            $customerTokens = $customer->tokens;
            foreach ($customerTokens as $token) {
                $token->revoke(); // Revoke each token
            }

            // Alternatively, if you want to delete the tokens completely (Laravel 8+ for Passport)
            // $customer->tokens()->delete();

            return response()->json([
                'message' => 'Customer deleted successfully.',
                'status' => true,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Customer record not found or already marked as deleted.',
                'status' => false,
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error updating customer record: ' . $e->getMessage(),
            'status' => false,
        ], 500);
    }
}

}
