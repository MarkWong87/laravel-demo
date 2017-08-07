<?php
$app->get('/','V1\ExampleController@testTr');

$api = app('Dingo\Api\Routing\Router');
$api->group(['version'=>['v1','v2'],'prefix' => 'v1','namespace' => 'App\Http\Controllers\V1','middleware'=>'App\Http\Middleware\ProfileDingoHttpResponse'], function () use ($api) {

    $api->get('/query/rank', 'QueryController@rank');
    $api->get('/query/hotTags', 'QueryController@hotTags');
    $api->get('/query/module', 'QueryController@module');
    $api->get('/article/ad', 'ArticleController@getAd');
    $api->get('/article/list', 'ArticleController@getList');
    $api->get('/article/recommend', 'ArticleController@getRecommend');
    $api->get('/article/hot', 'ArticleController@getHot');
    $api->get('/article/reply', 'ArticleController@getReply');
    /**
     * PagesController
     */
    $api->get('pages', 'PagesController@index');
    /**
     * ChannelController
     */
    $api->get('channel', 'ChannelController@index');
    /**
     * GlobalController
     */
    $api->get('global',     'GlobalController@index');
    $api->get('hotword',    'GlobalController@hotWord');
    $api->get('seatext',    'GlobalController@seatext');
    $api->get('banner',     'GlobalController@banner');
    $api->get('navigations','GlobalController@navigations');
    $api->get('pendant',    'GlobalController@pendant');

    $api->get('index/{phpinfo}', 'ExampleController@index');
});

$api->group(['version'=>['v1','v2'],'prefix' => 'v2','namespace' => 'App\Http\Controllers\V2'], function () use ($api) {
    $api->get('channel', 'ChannelController@index');
	$api->get('bangumi/page','BangumiController@page');

});
