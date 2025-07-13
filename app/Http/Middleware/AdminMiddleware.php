<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminMiddleware
{
  /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login dulu.');
        }

        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Anda bukan admin.');
        }

        return $next($request);
    }
}
