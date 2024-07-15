<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


//        if ($user->hasRole('admin')) {
//            return redirect()->route('admin.dashboard');
//        } elseif ($user->hasRole('user')) {
//            return redirect()->route('user.dashboard');
//        }else{
//            return 'error';
//        }
        return $next($request);
    }
}
