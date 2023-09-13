<?php

namespace App\Http\Middleware;

use App\Http\interfaces\CustomTokenInterface;
use App\Services\Auth\CustomToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheckerRegister
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

        $user = $this->customToken->auth($request->header('Authorization'));
        $user = (json_decode($user, true));
        $role = (last(request()->segments()));

        if(($role == 'admin' || $role == 'author'))
            if($user && $user["role"] == 'admin')
                return $next($request);
            else
                return response()->json(['status'=>'Forbidden'], 403);
        return $next($request);
    }
}
