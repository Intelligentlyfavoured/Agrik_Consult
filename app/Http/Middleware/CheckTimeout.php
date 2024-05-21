<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity');
            $timeout = 1; // Timeout time in minutes

            if ($lastActivity && Carbon::parse($lastActivity)->addMinutes($timeout)->lessThan(Carbon::now())) {
                Auth::logout();
                return redirect()->route('lockscreen');
            }

            Session::put('last_activity', Carbon::now());
        }

        return $next($request);
    }
}
