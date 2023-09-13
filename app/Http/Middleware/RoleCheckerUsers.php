<?php

namespace App\Http\Middleware;

use App\Services\Auth\CustomToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheckerUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user;
        if($user && ($user["role"] == 'admin' || $user["role"] == 'author'))
            return $next($request);
        else
            return response()->json(['status'=>'Forbidden'], 403);
    }
}
