<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Auth;

class CanCallApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::user()->isAdmin()){
            echo "error";
            $notification = array(
                'message'    => 'Not authorized to call this !!',
                'alert-type' => 'error',
            );  
            return back()->with($notification);
        }
        return $next($request);
    }
}
