<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| First we need to get an application instance. This creates an instance
| of the application / container and bootstraps the application so it
| is ready to receive HTTP / Console requests from the environment.
|
*/
define('LUMEN_START', microtime(true));

if (array_key_exists('adebug', $_GET) && $_GET['adebug']) {
    tideways_enable(TIDEWAYS_FLAGS_MEMORY | TIDEWAYS_FLAGS_CPU);
    register_shutdown_function(function () {
        $xhprof_data = tideways_disable();
        //php结束后继续运行
        if (function_exists('fastcgi_finish_request')) {
            //fastcgi_finish_request();
        }
        include_once "/data/wwwroot/xhprof/xhprof_lib/utils/xhprof_lib.php";
        include_once "/data/wwwroot/xhprof/xhprof_lib/utils/xhprof_runs.php";
        $xhprof_runs = new XHProfRuns_Default();
        $run_id = $xhprof_runs->save_run($xhprof_data, 'ac');
        /**
         * 查看地址：http://profiler.acadmin.com/xhprof_html/
         */
        //echo '<a href="http://profiler.acadmin.com/xhprof_html/index.php?run=' . $run_id . '&source=ac" target="_blank">profile</a>';
    });
}

$app = require __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$app->run();
