<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Pagination\Paginator; 

class NotificationsController extends Controller
{
    public function myNotifications($page_id)
    {
        $customer_id = Auth::id();
        
        // Set the current page for pagination manually if $page_id is set
        Paginator::currentPageResolver(function() use ($page_id) {
            return $page_id;
        });
    
        // Retrieve the notifications for the current page
        $notifications = Notification::where('customer_id', $customer_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        // Prepare the response
        $response = response()->json([
            'status' => true,
            'Notification' => $notifications
        ]);
    
        // Update the notifications as seen
        foreach ($notifications as $notification) {
            $notification->update([
                'seen_type' => true,
                'live_type' => true
            ]);
        }
    
        return $response;
    }



    public function sentNotification($customer_id ,$title ,$body, $ar_title, $ar_body )
    {
        $data = [
            'title' => $title,
            'ar_title' => $ar_title,
            'body' => $body,
            'ar_body' =>$ar_body
        ];

        $user = Customer::find($customer_id) ;
        if (!$user) {
            return ;
        }
        $deviceTokens = $user->deviceTokens()->pluck('device_token')->toArray();

        if (empty($deviceTokens)) {
            return ;
        }
        $options = array(
            'notification' => array(
                'badge' => 1,
                'sound' => 'ping.aiff',
                'title' => $title, // Use method parameter
                'body'  => $body, // Use method parameter
                'ar_title' => $ar_title, // Use method parameter
                'ar_body'  => $ar_body // Use method parameter
            )
        );

        foreach ($deviceTokens as $deviceToken) {
            $this->sendPushNotification($data, [$deviceToken], $options);
        }
    }

    public function sendPushNotification($data, $to, $options) {
        // Insert your Secret API Key here
        $apiKey = 'b64b5adc413a6de5b817b0e25c2325aa1f035b1da568fc0d0a5f0be11d29aad5';

        // Default post data to provided options or empty array
        $post = $options ?: array();

        // Set notification payload and recipients
        $post['to'] = $to;
        $post['data'] = $data;

        // Set Content-Type header since we're sending JSON
        $headers = array(
            'Content-Type: application/json'
        );

        // Initialize curl handle
        $ch = curl_init();

        // Set URL to Pushy endpoint
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushy.me/push?api_key=' . $apiKey);

        // Set request method to POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Set our custom headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Get the response back as string instead of printing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set post data as JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));

        // Actually send the push
        $result = curl_exec($ch);

        // Display errors
        if (curl_errno($ch)) {
            echo curl_error($ch);
        }

        // Close curl handle
        curl_close($ch);

        // Attempt to parse JSON response
        $response = @json_decode($result);

        // Throw if JSON error returned
        if (isset($response) && isset($response->error)) {
            return response()->json([
                'status' => false ,
                'data'  => $response->error
            ]);
        }
    }
    
    public function unread_notifications(){
      $count = Notification::where('customer_id', auth()->id())->where('seen_type',false)->count();
      return response()->json([
                'status' => true ,
                'count'  => $count
            ]);
    }
}
