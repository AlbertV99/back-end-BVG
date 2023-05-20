<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next){
        if ($request->getMethod() === 'OPTIONS') {
         	return response('', 200)
               	->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
            }
	    return $next($request)
		    ->header('Access-Control-Allow-Origin', '*')
		    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
		    ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
    }

}
