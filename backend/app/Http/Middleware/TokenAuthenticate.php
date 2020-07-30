<?php

namespace App\Http\Middleware;

use Closure;

class TokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->cookie('Auth-Token');
        if($token == env('API_TOKEN','wjMHcrk2HnJyjgaKJ5TMwN9fnVXicmjNv2TdXEKM9TludgIvGvx2kQmGM6MAhdos')){
            return $next($request);
        } else {
            return response()->json(['error' => 'UNAUTHORIZED'], 401);
        }
    }
}
