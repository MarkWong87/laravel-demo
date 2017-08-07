<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * version v1
     * @param $url
     * @return string
     */
    public static function appUrl($url){
        return 'http://'.$_ENV['API_DOMAIN'].'/v1/'.$url;
    }
}
