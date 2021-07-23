<?php

namespace App\Http\Controllers\Payments\Methods;

use App\Http\Controllers\Payments\PaymentHandler;
use Illuminate\Http\Request;
use App\Http\Controllers\Warehouses\Frisbo;
use App\Base as Model;
use GuzzleHttp\Client;

class Paydo extends PaymentHandler
{
    public function pay()
    {
        try {
            $client     = new \GuzzleHttp\Client();
            $amount     = self::$amount['main']['total'];
            $dataSet[]  = 'a4e9259c213ef4e0add2f9c4';
            $orderHash  = ['id' => self::$order->id, 'amount' => $amount, 'currency' => 'EUR'];
            ksort($orderHash, SORT_STRING);
            $dataSet    = array_values($orderHash);
            $signature  = hash('sha256', implode(':', $dataSet));
            $url        = "https://paydo.com/v1/invoices/create";
            $tokenPaydo = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjM1NCIsImFjY2Vzc1Rva2VuIjoiYmY2M2FjYjY2YmExMWViZjNjNDYzNDhlIiwidG9rZW5JZCI6IjU0Iiwid2FsbGV0SWQiOiIzNTQiLCJ0aW1lIjoxNTk1MDk3MDU5LCJleHBpcmVkQXQiOm51bGwsInJvbGVzIjpbXSwidHdvRmFjdG9yIjp7InBhc3NlZCI6ZmFsc2V9fQ.bBkxi7Qve4KmCfizKtAJVQtpVkCP6sY5Vyc7P9yXbnI';

            $request = $client->post($url, [
                'headers' => [
                        'Authorization' =>  "Bearer {$tokenPaydo}",
                        'Content-Type' => 'application/json',
                    ],
                'json' => [
                    "publicKey" => 'application-61c29b2e-e99e-45ea-88d9-1712b9c55bc6',
                    "order" => [
                            "id" => self::$order->id,
                            "amount" => $amount,
                            "currency" =>  "EUR",
                            "items" => [
                                [
                                   "id" => "1",
                                   "name" => "ds",
                                   "price" => $amount
                               ],
                            ],
                            "description" => ""
                        ],
                        "signature" => $signature,
                        "payer" => [
                            "email" => self::$order->details->email,
                            "phone" => '+'. self::$order->details->code .' '. self::$order->details->phone,
                            "name" => self::$order->details->contact_name,
                            "extraFields" => []
                        ],
                        "paymentMethod" => $methodId,
                        "language" => "en",
                        // "resultUrl" => url($this->lang->lang.'/order/payment/success/'.self::$order->id),
                        // "failPath" =>  url($this->lang->lang.'/order/payment/fail/'.self::$order->id),
                        "resultUrl" => URL::route('paydo-success', ['orderId' => self::$order->id, 'payment' => self::$payment]),
                        "failPath" =>  URL::route('paydo-fail',    ['orderId' => self::$order->id, 'payment' => self::$payment]),
                    ],
            ]);

            $invoceId = json_decode($request->getBody()->getContents());
            return redirect('https://paydo.com/en/payment/invoice-preprocessing/'.$invoceId->data);
        } catch (\Exception $e) {
            $problem = "Payment Paydo error.";
            LogsHandler::save(debug_backtrace(), $problem, \Auth::guard('persons')->user());
        }
    }

    public function getSuccessStatus($orderId, $payment)
    {
        self::$order = CRMOrders::find($orderId);
        self::$payment = $payment;

        $this->success();
        return redirect()->route('thanks', ['redirs' => 'success', 'checkout' => self::$order->id, 'promocode' => self::$promocode->id]);
    }

    public function getFailStatus($orderId, $payment)
    {
        self::$order = CRMOrders::find($orderId);
        self::$payment = $payment;

        $this->fail();
    }

    public function callBack()
    {
        // code...
    }
}
