<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/03/2017
 * Time: 2:05 PM
 */
namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Http\Response;
//use Illuminate\Http\Response;

class ProfileDingoHttpResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);


        if (
            $response instanceof Response &&
            app()->bound('debugbar') &&
            app('debugbar')->isEnabled()
        ) {

            $response->setContent(json_decode($response->morph()->getContent(), true)
                + ['debugbar' => app('debugbar')->getData()]
            );
        }

        return $response;
    }
}