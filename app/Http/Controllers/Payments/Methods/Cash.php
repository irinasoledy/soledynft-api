<?php

namespace App\Http\Controllers\Payments\Methods;

use App\Http\Controllers\Notifications\LogsHandler;
use App\Http\Controllers\Payments\PaymentHandler;

class Cash extends PaymentHandler
{
    public function pay()
    {
        try {
            $this->success();
            return redirect()->route('thanks', ['redirs' => 'success', 'checkout' => self::$order->id, 'promocode' => self::$promocode->id]);
        } catch (\Exception $e) {
            $problem = "Payment Cash on delivery error.";
            LogsHandler::save(debug_backtrace(), $problem, \Auth::guard('persons')->user());
            $this->fail();
            return redirect('/en/cart');
        }
    }
}
