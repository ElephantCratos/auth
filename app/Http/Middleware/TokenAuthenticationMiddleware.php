<?php

namespace App\Http\Middleware;

use App\Models\Token;
use App\Models\User;
use App\Services\TokenService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthenticationMiddleware
{
    protected $tokenService;
    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $accessToken = $request->cookie('access_token');
        

        $user = $this->getUserFromToken($accessToken);

        if (!$user){
            $this->refreshAccessTokenIfExist($request);
        }
        
        if ($user) {
            Auth::guard('api')->setUser($user);
            
        } else if (! $request->is('login'))  {
                return redirect()->route('login');
        }

        return $next($request);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function getUserFromToken($token)
    {
        if (empty($token)) {
            return null;
        }

        $tokenModel = Token::where('token', hash('sha256', $token))
                            ->where('type', 'access')
                            ->first();
        
        return $tokenModel ? $tokenModel->user : null;
    }

    public function refreshAccessTokenIfExist(Request $request): ?User
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return null;
        }
        $newAccessToken = $this->tokenService->updateAccessToken($refreshToken);

        if (!$newAccessToken) {
            return null;
        }

        cookie()->queue('access_token', $newAccessToken->token, $newAccessToken->expires_at, '/', null, true, true);
        
        return $this->getUserFromToken($newAccessToken->token);
    }

}
