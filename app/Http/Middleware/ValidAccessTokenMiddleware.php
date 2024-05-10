<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidAccessTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      $user = User::where('access_token', $request->header('Authorization'))->first();
      auth()->logout();
      if(!$user)
        return response()->json(['error' => 'Token de acesso não autorizado.'], 401);

      auth()->login($user);
      return $next($request);
    }
}
