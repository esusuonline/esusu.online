<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckFspStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guard('fsp')->check()){
            $fsp = Auth::guard('fsp')->user();
            if ($fsp->status  && $fsp->ev  && $fsp->sv  && $fsp->tv) {
                return $next($request);
            } else {
                return redirect()->route('fsp.authorization');
            }
        }
        abort(403);
    }
}
