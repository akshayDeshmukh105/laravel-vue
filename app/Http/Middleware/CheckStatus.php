<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class CheckStatus
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
        if (Auth::user()->status == 'active') {
            return $next($request);
        } else {
            if ($request->ajax()) {
                $respnose['status'] = false;
                $respnose['message'] = Lang::get('custom.user_inactive');
                return \Response::json($respnose);
            } else {
                Auth::logout();
                return redirect('login')->withErrors(Lang::get('custom.user_inactive'));
            }
        }
    }
}
