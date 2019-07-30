<?php

namespace App\Http\Middleware;

use App\Helpers\BergUtils;
use Closure;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class TokenEntrustAbility extends BaseMiddleware
{
    public function handle($request, Closure $next, $roles, $permissions, $validateAll = false)
    {

        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
           return $this->response('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);

        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        if (!$request->user()->ability(explode('|', $roles), explode('|', $permissions), array('validate_all' => $validateAll))) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', 401, 'Unauthorized');
            //return BergUtils::return_types('401', 'Unauthorized',null);
           // throw new UnauthorizedHttpException('jwt-auth', 'Token not provided');
        }

        //$this->events->dispatch('tymon.jwt.valid', $user);
        Event::listen('tymon.jwt.invalid',$user);


        return $next($request);
    }
}
