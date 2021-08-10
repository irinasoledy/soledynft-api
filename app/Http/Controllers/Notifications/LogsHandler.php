<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Cart;
use Carbon\Carbon;

class LogsHandler extends Controller
{
    public static function save($debugTrace, $problem, $user)
    {
        try {
            $currenctTime = Carbon::now()->add(3, 'hour');
            $cartId = 0;
            $email  = null;

            $cart = Cart::where('user_id', $debugTrace[0]['object']->userId)->first();

            if (!is_null($cart)) $cartId = $cart->id;
            if (!is_null($user)) $email = $user->email;

            Log::create([
                'user_id'   => $debugTrace[0]['object']->userId,
                'cart_id'   => $cartId,
                'lang'      => $debugTrace[0]['object']->lang->lang,
                'device'    => $debugTrace[0]['object']->device,
                'currency'  => $debugTrace[0]['object']->currency->abbr,
                'country'   => $debugTrace[0]['object']->country->name,
                'user_email'=> $email,
                'problem'   => $problem,
                'file'      => $debugTrace[0]['file'],
                'controller'=> $debugTrace[0]['class'],
                'referrer'  => $debugTrace[1]['class'].'@'.$debugTrace[1]['function'],
                'method'    => $debugTrace[0]['function'],
                'date'      => $currenctTime->toDateTimeString(),

            ]);
        } catch (\Exception $e) {}
    }
}
