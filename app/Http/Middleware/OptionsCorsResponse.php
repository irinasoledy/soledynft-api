<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * OptionsCorsResponse middleware - add CORS headers if request method OPTIONS
 */
class OptionsCorsResponse
{
    /**
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
     public function handle($request, Closure $next)
     {
         if ($request->isMethod('OPTIONS')){
             $response = Response::make();
         } else {
             $response = $next($request);
         }
         return $response
             ->header('Access-Control-Allow-Origin', '*')
             ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
             ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application, X-CSRF-Token, x-requested-with');
     }
}
