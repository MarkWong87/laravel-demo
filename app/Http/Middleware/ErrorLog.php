<?php

namespace App\Http\Middleware;

use App\Lib\Helper;
use Closure;

class ErrorLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $info = $response->original;
        $runTime = intval( ( microtime(true) - LUMEN_START ) * 1000 );
        if ((isset($info['errorid']) && $info['errorid'] == 500)) {
            Helper::addLog('ERROR', $info['errorid'], $request->getPathInfo(), $runTime, $request->server('QUERY_STRING'), $info['errordesc']);
        } elseif (isset($info['errorid']) && $info['errorid'] != 0) {
            Helper::addLog('INFO', $info['errorid'], $request->getPathInfo(), $runTime, $request->server('QUERY_STRING'), $info['errordesc']);
        } elseif ($runTime > 500) {
            Helper::addLog('NOTICE', $info['errorid'], $request->getPathInfo(), $runTime, $request->server('QUERY_STRING'), 'time>500ms');
        }
        return $response;
    }
}
