<?php

namespace App\Http\Middleware;

use Closure;
use App\UserAccount;
use Illuminate\Support\Facades\Auth;

class DeanMiddleware
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
        if(!Auth::check()){
            return redirect()->route('index');
        }

        /* Get the id of User who logged in */
        $userId = Auth::id();
        $user = UserAccount::find($userId);

        /* Check User Type */
        if($user->user_type != 'Dean'){
            return redirect()->back();
        }
        
        return $next($request);
    }
}
