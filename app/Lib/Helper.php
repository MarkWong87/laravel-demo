<?php

/**
 * Created by PhpStorm.
 * User: markWong
 * Date: 2017/3/22
 * Time: 下午1:25
 */
namespace App\Lib;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;

class Helper
{
    public static function addLog($level, $errorNo, $pathInfo, $runTime, $queryString, $desc) {
        $logInfo = '['.$errorNo.'] ['.$pathInfo.'] ['.$runTime.'] ['.$queryString.'] ['.$desc.']';

        $dateTime = 'Ymd H:i:s';
        //$def = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
        $output = "[%level_name%] [%datetime%] %message%\n";

        $stream = new RotatingFileHandler(
            env('APP_LOG_PATH') ? env('APP_LOG_PATH') : storage_path('logs/lumen.log')
        );
        $stream->setFilenameFormat('{filename}_'.$level.'_{date}', 'Ymd');
        $stream->setFormatter(new LineFormatter($output, $dateTime));
        $log = new Logger('applog');
        $log->pushHandler($stream);
        switch ($level) {
            case 'INFO':
                $log->addInfo($logInfo);
                break;
            case 'ERROR':
                $log->addError($logInfo);
                break;
            case 'NOTICE':
                $log->addNotice($logInfo);
                break;
            default :
                $log->addDebug($logInfo);
        }
    }
}