<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\Request;

class CORS {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        header("Access-Control-Allow-Origin: *");

        // ALLOW OPTIONS METHOD
        // $headers = [
        //     'Access-Control-Allow-Origin'=> '*',
        //     'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
        //     'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin, x-csrf-token'
        // ];
        // if($request->getMethod() == "OPTIONS") {
        //     // The client-side application can set only headers allowed in Access-Control-Allow-Headers
        //     return Response::make('OK', 200, $headers);
        // }
        //
        // $response = $next($request);
        // foreach($headers as $key => $value)
        //     $response->header($key, $value);
        // return $response;

       $response = $next($request);
       $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, DELETE, OPTIONS');
       $response->header('Access-Control-Allow-Headers', 'x-csrf-token', $request->header('Access-Control-Request-Headers'));
       // $response->header('Access-Control-Allow-Origin', 'http://localhost:3002/');
       return $response;
    }

}
