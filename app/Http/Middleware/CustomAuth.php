<?php

namespace App\Http\Middleware;

use App\Http\interfaces\CustomTokenInterface;
use App\Services\Auth\CustomToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuth
{
    protected $customToken;
    public function __construct(CustomTokenInterface $customToken)
    {
        $this->customToken = $customToken;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($user = $this->customToken->auth($request->header('Authorization')))
        {
            $request->merge(['user' => (json_decode($user, true))]);
            return $next($request);
        }
        else
        {
            return response()->json(['status'=>'Unauthorized'], 401);
        }
    }
}
