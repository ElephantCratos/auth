<?php

namespace App\Http\Middleware;



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
        

        $user = $this->tokenService->getUserFromToken($accessToken);

        if (!$user){
            $this->tokenService->refreshAccessTokenIfExist($request);
        }
        
        if ($user) {
            Auth::guard('api')->setUser($user);
            
        } else if (! $request->is('login') && ! $request->is('phone-login'))  {
                return redirect()->route('login');
        }

        return $next($request);
    }

    


   

}
