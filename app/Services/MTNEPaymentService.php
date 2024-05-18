<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use function Illuminate\Events\queueable;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class MTNEPaymentService
{
    protected $client;
    protected $baseUri;
    protected $privateKey;
    protected $pubKey;
    protected $publicKey;

    public function __construct()
    {
      $this->client = new Client();
        $this->baseUri = 'https://cashmobile.mtnsyr.com:9000';
        $passphrase = '0930';
        $encryptedPrivateKeyString  = "-----BEGIN ENCRYPTED PRIVATE KEY-----\nMIIC5TBfBgkqhkiG9w0BBQ0wUjAxBgkqhkiG9w0BBQwwJAQQJt7yD4w6b4cpAZq0\nPo8BlQICCAAwDAYIKoZIhvcNAgkFADAdBglghkgBZQMEASoEELEUNzK5erK8kisg\nPV4VP4kEggKAItkWjK1l04GWyad7SheaQywIYDILjR20b2111GFp9DqM5SUOf+c3\nLCijSJRNnIS9Tsw3ABDkkiFnkIBYl6xMcXGR8jgdOJRCIRQW2DmGC9Q1DD4i8cB3\nloj/wMhJnbvxMaM2njPEq1jlyrE6Ws/wPHt7F9izSSnujhaPgR3oA+PGQxy4GQy8\nOpcyZY0Eg58Bu6YE9B7BRPPMhJLZFaHiUffQnNNW7UMACsTaPKgnI7Bq6TC4kIhD\noMCdsmA1Ja/e38ktaH3RxQAIIcnt6Nx3HaB7QJMbV5bsv2sph1x4zVPlLp3zy/FS\n/uQ5GH+DSpACLaQC+MBuA+lud8P7sR6btY+bzL1Vl8uxDMOkDBfzHiof8Y6/x6E2\n1oDZxergLgxmQtJVz2V5zsNSguJuO9UugFxP1Gzlhc17NVs8fEzsIWx0OkKs9H4s\ngkDquDvmqfw2zlvW1QQX8G5FD29+yicBHuD7UxbDjbvq7mjyqASsVBmHwMIF3yRI\nChdMSeL5RfE0lsxvsQpN9MYNVzPZ3y48FpsmNxoK8mf5iv8bK9ANC8sshlfAOQ5x\ntXV6NmileoiSIAG/ifpJvbOxA1vbDyf7d/Gff8hR+wT8NnBzLgzVBFMSpix6Rsnw\n0yihDNs8YTBOIlX8sEhrYaxr0Pg7tpYWIJj+YjM6guptUDEkFjrv7axLoQJnBD82\nD2eoAJquVM3Nx/1YL1SHbo1T9O0galKtpUQXNEh26TW/z1hzpGIBRxHcNG3Nj6U8\nb8GhXXYGI51C0B4kkpwrMHrqMPjEilI1PWVXfA/TFeaOTsGHN/bxGSOYRoiYKvB2\nyCFCfs8/B/UghjpVQcA4gJCT+yb09nEqzw==\n-----END ENCRYPTED PRIVATE KEY----- " ;
        $this->privateKey  = openssl_pkey_get_private($encryptedPrivateKeyString ,$passphrase);
        $this->pubKey = "-----BEGIN PUBLIC KEY-----\nMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCu7RgFPADYS2FduDYU1rMYPfvl\nDCTGrjqH8QrxaNWHQ5Z0zZu3b4UZk6QphIt1Llqpt9QSjcmJokSFU0HtG/6u1cL0\nt/lrq/62o7XvgGwlzfFX/1vMVYWXbK37Kt2O7/hxA7+v2vi7PChXlPVkMF0XuT1s\nJQUnUQUSfcBetithtwIDAQAB\n-----END PUBLIC KEY-----" ;
        $this->publicKey = openssl_pkey_get_public($this->pubKey);
    }

    public function terminalActivation()
    {
        $requestData = [
            "Secret" => "18999958",
            "Key" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCu7RgFPADYS2FduDYU1rMYPfvlDCTGrjqH8QrxaNWHQ5Z0zZu3b4UZk6QphIt1Llqpt9QSjcmJokSFU0HtG/6u1cL0t/lrq/62o7XvgGwlzfFX/1vMVYWXbK37Kt2O7/hxA7+v2vi7PChXlPVkMF0XuT1sJQUnUQUSfcBetithtwIDAQAB", // Remove line breaks for one-line format
            "Serial" => "9308426"
        ];

        $base64Signature =$this->generateXSignature($requestData);

        echo 'X-Signature: ' . $base64Signature . "\n";
    }

    protected function generateXSignature($requestData)
    {
        // First, convert the array to a JSON string
        $jsonRequest = json_encode($requestData);

        // Sign the request using the private key
        openssl_sign($jsonRequest, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);

        // This is just for demonstration. In actual use, verification would be done by the server receiving the request.
//        $verificationResult = openssl_verify($hashedRequest, $signature, $this->publicKey, OPENSSL_ALGO_SHA256);
//        echo "Verification: " . ($verificationResult === 1 ? "Success" : "Failure") . "\n";

        // Convert the signature to Base64 format
        return  base64_encode($signature);
    }

    public function createInvoice($amount, $sessionId , $customer_phone , $description)
{    
        $invoice = Invoice::create([
            'customer_id' => Auth::id(),
            'amount' => $amount,
            'description' => $description
        ]);
 
        $uuid = $invoice->external_id; // Assuming $invoice->id contains a UUID string.
        $numericHash = crc32($uuid);

        $amount = $amount * 100;
        $requestData = [
            'Amount' => $amount,
            'Invoice' => $numericHash  ,
            'Session' => $sessionId,
            'TTL' => 15
        ];

        $base64Signature = $this->generateXSignature($requestData);


        $response = $this->client->post($this->baseUri, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Signature' => $base64Signature,
                'Subject' => '9001000000049890',
                'Request-Name' => 'pos_web/invoice/create',
                'Accept-Language' => 'en'
            ],
            'json' => [
                'Amount' => $amount,
                'Invoice' => $numericHash ,
                'Session' => $sessionId,
                'TTL' => 15,
            ]
        ]);
      $invoiceResponse = json_decode($response->getBody()->getContents(), true);

        if (isset($invoiceResponse['Errno']) && $invoiceResponse['Errno'] === 0) {
            $paymentResponse = $this->initiatePayment($invoice->id, $customer_phone , $numericHash);
            if($paymentResponse[0]) {
                return response()->json([
                    'status' => true,
                    'message' => $invoiceResponse,
                    'invoice_id' => $invoice->id
                ]);
            } else {
                $invoice->delete();
                return response()->json([
                    'status' => false,
                    'message' => $paymentResponse[1]
                ]);
            }
        }
        else {
            $invoice->delete();
            return response()->json([
                'status' => false,
                'message' => $invoiceResponse
            ]);
        }
    }

    protected function getInvoice($invoiceId)
    {
        $requestData = [
            'Invoice' => $invoiceId ,
        ] ;

        $base64Signature =$this->generateXSignature($requestData);

        $response = $this->client->post($this->baseUri , [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Signature' => $base64Signature,
                'Subject' => '9001000000049890' ,
                'Request-Name' => 'pos_web/invoice/get' ,
                'Accept-Language'=> 'en'
            ],'json' => [
                'Invoice' => $invoiceId,
            ]
        ]);

        return  json_decode($response->getBody()->getContents(), true);
    }

    public function initiatePayment($invoiceId, $customerPhone, $numericHash)
    {
      $payment = Payment::create([
            'invoice_id' => $invoiceId
        ]);

        $sumResult = $payment->external_id  ;
        $stringResult = strval($sumResult);

        $requestData = [
            'Invoice' => $numericHash  ,
            'Phone' => $customerPhone,
            'Guid' => $stringResult
        ];

        $base64Signature = $this->generateXSignature($requestData);

        $response = $this->client->post($this->baseUri, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Signature' => $base64Signature,
                'Subject' => '9001000000049890',
                'Request-Name' => 'pos_web/payment_phone/initiate',
                'Accept-Language' => 'en'
            ], 'json' => [
                'Invoice' => $numericHash  ,
                'Phone' => $customerPhone,
                'Guid' => $stringResult
            ]
        ]);
        $paymentResponse = json_decode($response->getBody()->getContents(), true);
        if (isset($paymentResponse['Errno']) && $paymentResponse['Errno'] === 0) {
            $operation_number = $paymentResponse['OperationNumber'];
            $payment->update([
                'operation_number' => strval($operation_number)
            ]);
            
            return [true];
        }else {
            $payment->delete();
            return [false,$paymentResponse];
        }
    }

    public function confirmPayment($invoiceId,$mtnInvoiceId, $phone, $code)
    {

        $payment = Payment::where('invoice_id' , intval($invoiceId))->first() ;

        //operation number as number
        $operationNumber= intval($payment->operation_number);

        //GUID as String
        $sumResult = $payment->external_id ;
        $stringResult = strval($sumResult);

        $code = base64_encode(hash('sha256', $code,true));

        $requestData = [
            'Phone' => $phone ,
            'Guid' => $stringResult,
            'OperationNumber' => $operationNumber ,
            'Invoice' => $mtnInvoiceId ,
            'Code' => $code
        ] ;

        $base64Signature =$this->generateXSignature($requestData);

        $confirmResponse = $this->client->post($this->baseUri, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Signature' => $base64Signature,
                'Subject' => '9001000000049890' ,
                'Request-Name' => 'pos_web/payment_phone/confirm' ,
                'Accept-Language'=> 'en'
            ],'json' => [
                'Phone' => $phone ,
                'Guid' => $stringResult,
                'OperationNumber' => $operationNumber ,
                'Invoice' => $mtnInvoiceId ,
                'Code' => $code
            ]
        ]);

        $confirmResponse =  json_decode($confirmResponse->getBody()->getContents(), true);
        
        if (isset($confirmResponse['Errno']) && $confirmResponse['Errno'] === 0) {
            return response()->json([
                'status' => true ,
                'message' => 'Paid Successfully'
            ],200);
        }else{
            //$invoice = Invoice::find($invoiceId) ;
            //$invoice->delete();
            
            return response()->json([
                'status' => false ,
                'message' => $confirmResponse
            ],402);
        }
    }

    public function initiateRefund($invoice_id)
    {
        $requestData = [
            'Invoice' => $invoice_id,
        ];

        $base64Signature = $this->generateXSignature($requestData);

        $response = $this->client->post($this->baseUri, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Signature' => $base64Signature,
                'Subject' => '9001000000049890',
                'Request-Name' => 'pos_web/invoice/refund/initiate',
                'Accept-Language' => 'en'
            ], 'json' => [
                'Invoice' => $invoice_id,
            ]
        ]);

        $initiateRefundResponse = json_decode($response->getBody()->getContents(), true);
        if (isset($initiateRefundResponse['Errno']) && $initiateRefundResponse['Errno'] === 0) {
            return [true, $initiateRefundResponse['RefundInvoice'], $invoice_id];
        } else {
            return [false, $initiateRefundResponse];
        }
    }

    public function confirmRefund($baseInvoiceId, $refundInvoiceId)
    {
        $requestData = [
            'BaseInvoice' => $baseInvoiceId,
            'RefundInvoice' => $refundInvoiceId
        ];

        $base64Signature = $this->generateXSignature($requestData);

        $response = $this->client->post($this->baseUri, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Signature' => $base64Signature,
                'Subject' => '9001000000049890',
                'Request-Name' => 'pos_web/invoice/refund/confirm',
                'Accept-Language' => 'en'
            ], 'json' => [
                'BaseInvoice' => $baseInvoiceId,
                'RefundInvoice' => $refundInvoiceId
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        if (isset($responseData['Errno']) && $responseData['Errno'] === 0) {
            return true;
        } else {
            return false;
        }
    }
}
