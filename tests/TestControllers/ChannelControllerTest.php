<?php

use App\Constants\ErrorCode;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Repositories\V1\ChannelInterface;

class ChannelControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /**
     * @dataProvider urlProvider
     */
    public function testResponse($url)
    {
        $response = $this->get($url);
        $response->assertResponseOk();
        $response->seeJson(['name' => 'channel', 'errorid' => 0, 'errordesc' => '', 'version' => 'v1', 'allversion' => ['v1', 'v2']]);
    }

    public function urlProvider()
    {
        return [
            "normal without cid" => [self::appUrl("channel")],
            "cid equal 70" => [self::appUrl("channel?cid=70")],
            "cid is string" => [self::appUrl("channel?cid=ab,''c")],
            "cid has quote" => [self::appUrl("channel?cid='/sdf")],
            "resource is mysql" => [self::appUrl("channel?cid=70&resource=mysql")],
            "resource is es" => [self::appUrl("channel?cid=70&resource=es")],
            "resource is informal" => [self::appUrl("channel?cid=70&resource=nothing")],
        ];
    }

    /**
     *
     */
    public function testDbQuery()
    {

        $arrInitData = [
            'ac_web_page' => [
                [
                    'id' => '1',
                    'name' => 'http://www.acfun.tv/v/ac2397295',
                    'cid' => '1',
                    'status' => '0',
                    'site_id' => '1'
                ],
                [
                    'id' => '2',
                    'name' => 'page 2',
                    'cid' => '1',
                    'status' => '1',
                    'site_id' => '1'
                ],
                [
                    'id' => '3',
                    'name' => 'page 3333333',
                    'cid' => '1',
                    'status' => '2',
                    'site_id' => '1'
                ],
                [
                    'id' => '4',
                    'name' => 'page 44',
                    'cid' => '1',
                    'status' => '0',
                    'site_id' => '1'
                ],
            ],
        ];

        /**
         * 返回结果是0=>[]，并且id值为int型，需要做额外处理
         */
        $arrExcept = [
            0 => [
                'id' => 1,
                'name' => 'http://www.acfun.tv/v/ac2397295',
                'cid' => 1,
            ]
        ];

        $nErrCodeExt = ErrorCode::ERROR_PROMPT_SUCCESS;

        //	初始化数据库
        $oDbUnit = new MyDbUnit([], $arrInitData);

        //	验证数据
        $channel = new \App\Repositories\V1\ChannelImplement();
        $res = $channel->getChannel(['id', 'name', 'cid']);

        $this->assertArraySubset($arrExcept, $res, "channel列表", true);
    }


}
