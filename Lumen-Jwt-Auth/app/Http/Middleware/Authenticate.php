<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;
    protected $jwtAuth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth, JWTAuth $jwtAuth)
    {
        $this->auth = $auth;
        $this->jwtAuth = $jwtAuth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // // cek jika gk ada token
        if($this->auth->guard($guard)->guest()) {
            return response([
                "error" => true,
                "code" => 401,
                "message" => 'Unauthorized.',
            ], 401);
        }

        $this->authenticate($request);

        // cek sama gk token yg dikirim sama yg didatabase
        if($this->jwtAuth->getToken() != $this->auth->guard($guard)->user()->api_token)
        {
            return response([
                "error" => true,
                "code" => 401,
                "message" => 'Unauthorized.',
            ], 401);
        }

        return $next($request);
    }

    protected function checkForToken(Request $request)
    {
        if (! $this->jwtAuth->parser()->setRequest($request)->hasToken()) {
            return response([
                "error" => true,
                "code" => 401,
                "message" => 'Unauthorized.',
            ], 401);
        }
    }

    protected function authenticate(Request $request)
    {
        $this->checkForToken($request);

        try {
            if (! $this->jwtAuth->parseToken()->authenticate()) {
                throw new UnauthorizedHttpException('jwt-auth', 'User not found');
            }
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }
    }

    /**
     * Set the authentication header.
     *
     * @param  \Illuminate\Http\Response|\Illuminate\Http\JsonResponse  $response
     * @param  string|null  $token
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function setAuthenticationHeader($response, $token = null)
    {
        $token = $token ?: $this->jwtAuth->refresh();
        $response->headers->set('Authorization', 'Bearer '.$token);

        return $response;
    }
}
