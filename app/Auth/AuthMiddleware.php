<?php

namespace Rapid\Auth;

use Rapid\Models\User;

class AuthMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if($request->getUri()->getPath() != '/login') {
            if( User::isLogged() ) {
                $response = $next($request, $response);
                return $response;
            }
            
            return $response->withRedirect('/login', 500);
        }
    }
}