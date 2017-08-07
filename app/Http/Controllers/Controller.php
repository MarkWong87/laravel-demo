<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Laravel\Lumen\Routing\Controller as BaseController;
class Controller extends BaseController
{
    use Helpers;

    public function responseFormat($vdata, $url, $version = 'v1', $name = 'search', $errorid = 0, $errordesc = '', $allversion = ['v1'])
    {
        $return['name'] = $name;
        $return['url'] = $url;
        $return['errorid'] = $errorid;
        $return['errordesc'] = $errordesc;
        $return['vdata'] = $vdata;
        $return['version'] = $version;
        $return['allversion'] = $allversion;
        return response($return)->header('Access-Control-Allow-Origin', '*');
    }
}
