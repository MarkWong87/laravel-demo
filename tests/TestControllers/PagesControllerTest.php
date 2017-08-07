<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PagesControllerTest extends TestCase
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
        $response->seeJson(['name' => 'pages', 'errorid' => 0, 'errordesc' => '', 'version' => 'v1', 'allversion' => ['v1']]);
    }
    public function urlProvider()
    {
        return [
            [self::appUrl("pages")],
            [self::appUrl("pages?cid=70")],
            [self::appUrl("pages?cid=70&resource=mysql")],
        ];
    }

    //验证数据
    public function testDbQuery()
    {
        $arrInitData = [
            'ac_web_block' =>[
                [
                    'id'=>154,
                    'page_id'=>10,
                    'color'=>'',
                    'block_type'=>26,
                    'is_deleted'=>0,
                    'name'=>'轮播图＋6小视频1',
                    'orders'=>101,
                    'status'=>0,
                    'style'=>0
                ]
            ],
            'ac_web_page' =>[
                [
                    'id'=>10,
                    'name'=>'科技二级页',
                    'orders'=>93,
                    'status'=>0,
                    'creater_id'=>1,
                    'cid' =>70,
                    'site_id'=>1,
                ]
            ],
            'ac_web_module' =>[
                [
                   'id'=>230,
                   'block_id'=>154,
                   'name'=>'轮播图',
                   'module_type'=>1,
                   'content_count'=>5,
                   'orders'=>4,
                   'status'=>0,
                   'is_deleted'=>0,
                   'creator_id'=>0,
                   'onepage_count'=>4
                ],
                [
                    'id'=>231,
                    'block_id'=>154,
                    'name'=>'小图综合推荐',
                    'module_type'=>2,
                    'content_count'=>11,
                    'orders'=>3,
                    'status'=>0,
                    'is_deleted'=>0,
                    'creator_id'=>0,
                    'onepage_count'=>4
                ]
            ]
        ];
        $arrDbExcept = [
                [
                    'id'=>154,
                    'color'=>'',
                    'block_type'=>26,
                    'is_deleted'=>0,
                    'name'=>'轮播图＋6小视频1',
                    'cid'=>'',
                    'orders'=>101,
                    'page_id'=>10,
                    'status'=>0,
                    'style'=>0,
                    'module'=>[1,2]
                ]
        ];
        $oDbUnit = new MyDbUnit([], $arrInitData);
        //验证数据
        $pages = new \App\Repositories\V1\PagesImplement();
        $dt['cid'] = 70;
        $dt['resource'] = 'mysql';
        $field = ['ac_web_block.id','ac_web_block.color','ac_web_block.block_type',
            'ac_web_block.is_deleted','ac_web_block.name','ac_web_block.cid',
            'ac_web_block.orders','ac_web_block.page_id','ac_web_block.status','ac_web_block.style'];
        $blockResult = $pages->getBlock($dt,$field);
        $this->assertArraySubset($arrDbExcept, $blockResult, "所有区块列表", true);
    }
}
