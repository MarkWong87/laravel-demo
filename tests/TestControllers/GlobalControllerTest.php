<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Constants\TypeCode;
class GlobalControllerTest extends TestCase
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
        $response->seeJson(['errorid' => 0, 'errordesc' => '', 'version' => 'v1', 'allversion' => ['v1']]);

    }
    public function urlProvider()
    {
        return [
            [self::appUrl("global")],
            [self::appUrl("global?type=WEB")],
            [self::appUrl("global?type=WEB&resource=mysql")],
            [self::appUrl("navigations")],
            [self::appUrl("navigations?resource=mysql")],
            [self::appUrl("banner")],
            [self::appUrl("banner?resource=mysql")],
            [self::appUrl("hotword")],
            [self::appUrl("hotword?type=WEB")],
            [self::appUrl("hotword?type=WEB&resource=mysql")],
            [self::appUrl("seatext")],
            [self::appUrl("seatext?resource=mysql")],
            [self::appUrl("pendant")],
            [self::appUrl("pendant?resource=mysql")],
        ];
    }
    public function testDbQuery()
    {
        $arrInitData = [
            'ac_global_a' => [
                [
                    'global_id' =>1,
                    'global_name'	=> 'wantest',
                    'global_url'		=> 'http://www.acfun.tv/v/ac2397295',
                    'global_text'	=> '2015年12月17日，aa莱特兄弟带你飞至今已112年啦！',
                    'publish_date'  =>'2017-03-27 16:45:14',
                    'type'=>TypeCode::TYPE_BANNER,//默认词的话改为:TypeCode::TYPE_DEFAULT_WORD
                    'narrow_img'=>'',
                    'wide_img' =>'',

                ],


            ],
            'jc_hotword' => [
                [
                    'hotword_id' =>1,
                    'type'     =>'WEB',
                    'hotword'	=> 'wantest',
                    'orderby'  =>1

                ],
            ],
            'ac_web_nav' => [
                [

                    "cid"=>0,
                    "link"=>"http://www.acfun.cn/v/list144/index.htm",
                    "media_type"=>0,
                    "nav_name"=> "番剧",
                    "orders"=> 100,
                    "parent"=> 0,
                    "status"=> 1
                ],

            ],
            'ac_application_spread_content' => [
                [
                    'image'=>'http://imgs.aixifan.com/cms/2016_10_10/1476070361553.gif',
                    'is_delete'=>0,
                    'link'=>'http://www.acfun.tv/a/ad20160913',
                    'recommendation'=>13244,
                    'sort_time'=>'2016-10-10 03:32:49',
                    'spread_type_id'=>90,
                    'status'=>1,
                    'title'=>'2141321'
                ],

            ],
            'ac_application_spread_type' => [
                [
                    'id'=>90,
                    'name'=>'首页挂件',
                    'en_name'=>'indexguajian',
                    'status'=>1,
                    'interface_parameter'=>'indexguajian',
                    'interface_first_content_num'=>1,
                    'interface_paging_content_num'=>1,
                    'interface_total_content_num'=>1,
                    'sortId'=>1,
                    'remarks'=>1
                ],

            ],
        ];

        $arrDbExcept = [
            'ac_global_a' => [
                [
                    'global_id' =>1,
                    'global_name'	=> 'wantest',
                    'global_url'		=> 'http://www.acfun.tv/v/ac2397295',
                    'global_text'	=> '2015年12月17日，aa莱特兄弟带你飞至今已112年啦！',
                    'publish_date'  =>'2017-03-27 16:45:14',
                    'type'=>TypeCode::TYPE_BANNER,////默认词的话改为:TypeCode::TYPE_DEFAULT_WORD
                    'narrow_img'=>'',
                    'wide_img' =>''
                ]

            ],
            'jc_hotword' => [
                [
                    'hotword_id' =>1,
                    'type'     =>'WEB',
                    'hotword'	=> 'wantest',

                    'orderby'  =>1

                ],
            ],
            'ac_web_nav' => [
                [

                    "cid"=>0,
                    "link"=>"http://www.acfun.cn/v/list144/index.htm",
                    "media_type"=>0,
                    "nav_name"=> "番剧",
                    "orders"=> 100,
                    "parent"=> 0,
                    "status"=> 1
                ],

            ],
            'ac_application_spread_content' => [
                [
                    'image'=>'http://imgs.aixifan.com/cms/2016_10_10/1476070361553.gif',
                    'is_delete'=>0,
                    'link'=>'http://www.acfun.tv/a/ad20160913',
                    'recommendation'=>13244,
                    'sort_time'=>'2016-10-10 03:32:49',
                    'spread_type_id'=>90,
                    'status'=>1,
                    'title'=>'2141321'
                ],

            ],
            'ac_application_spread_type' => [
                [
                    'id'=>90,
                    'name'=>'首页挂件',
                    'en_name'=>'indexguajian',
                    'status'=>1,
                    'interface_parameter'=>'indexguajian',
                    'interface_first_content_num'=>1,
                    'interface_paging_content_num'=>1,
                    'interface_total_content_num'=>1,
                    'sortId'=>1,
                    'remarks'=>1
                ],

            ],
        ];

        //分别调用验证数据
        $this->checkHotWord($arrDbExcept['jc_hotword'],$arrInitData['jc_hotword']);
        $this->checkContent($arrDbExcept['ac_global_a'],$arrInitData['ac_global_a'],TypeCode::TYPE_BANNER);//搜索默认次的话为:TypeCode::TYPE_DEFAULT_WORD
        $this->checkNavigations($arrDbExcept['ac_web_nav'],$arrInitData['ac_web_nav']);
        //初始化数据
        $arr['ac_application_spread_type'] = $arrDbExcept['ac_application_spread_type'];
        $arrs['ac_application_spread_type'] = $arrInitData['ac_application_spread_type'];
        $oDbUnit = new MyDbUnit($arr,$arrs);
        $this->checkPendant($arrDbExcept['ac_application_spread_content'],$arrInitData['ac_application_spread_content']);

    }
    //检查热门搜索词
    public function checkHotWord($arrDbExcept,$arrInitData)
    {
        //方法一:可以展示出差异
        $arr['jc_hotword'] = $arrDbExcept;
        $arrs['jc_hotword'] = $arrInitData;
        $oDbUnit = new MyDbUnit($arr,$arrs);
        //验证数据库
        $oDbSet = $oDbUnit->getConnection()->createQueryTable('jc_hotword',
            'select * from jc_hotword where type = "WEB" order by orderby asc'
        );
        $oExceptTable = $oDbUnit->getTableSet('jc_hotword');
        return $oDbUnit->assertTablesEqual( $oDbSet, $oExceptTable );
        //方法二:直接检查model也可以
        /*
        $global = new \App\Repositories\V1\GlobalImplement();
        $data['type'] = 'WEB';
        $data['resource'] = 'mysql';
        $field = ['hotword_id','type','hotword','orderby'];
        $res = $global->getHotWord($data,$field);
        $this->assertArraySubset($arrDbExcept, $res, "banner与默认搜索词列表", true);
       */

    }
    //检查banner跟默认搜索词
    public function checkContent($arrDbExcept,$arrInitData,$type)
    {
        //方法一:可以展示出差异
        $arr['ac_global_a'] = $arrDbExcept;
        $arrs['ac_global_a'] = $arrInitData;
        $oDbUnit = new MyDbUnit($arr,$arrs);
         //	验证数据库
         $oDbSet = $oDbUnit->getConnection()->createQueryTable( 'ac_global_a',
             'select global_id,global_name,global_url,global_text,publish_date,type,narrow_img,wide_img from ac_global_a where type = "'.$type.'" and publish_date <= "2017-03-28 17:54:09" and status = 0 order by publish_date desc, global_id desc limit 1'
         );
         $oExceptTable = $oDbUnit->getTableSet( 'ac_global_a');
         return $oDbUnit->assertTablesEqual( $oDbSet, $oExceptTable );
        //方法二:直接检查model也可以
        /*
        $global = new \App\Repositories\V1\GlobalImplement();
        $param['time'] = date("Y-m-d H:i:s", time());
        $param['type'] = TypeCode::TYPE_BANNER;
        $param['resource'] =  'mysql';
        $field = ['global_id','global_name','global_url','global_text','publish_date','type','narrow_img','wide_img'];
        $res = $global->getContent($param,$field);
        $array[] = json_decode(json_encode($res),TRUE);
        $this->assertArraySubset($arrDbExcept, $array, "banner与默认搜索词列表", true);
        */

     }

    //检测导航数据
    public function checkNavigations($arrDbExcept,$arrInitData)
    {
        $arr['ac_web_nav'] = $arrDbExcept;
        $arrs['ac_web_nav'] = $arrInitData;
        $oDbUnit = new MyDbUnit($arr,$arrs);
        //	验证数据库
        $oDbSet = $oDbUnit->getConnection()->createQueryTable( 'ac_web_nav',
            'select cid,link,media_type,nav_name,orders,parent,status from ac_web_nav where is_delete = 0 and status = 1 order by orders desc,id desc'
        );
        $oExceptTable = $oDbUnit->getTableSet('ac_web_nav');
        return $oDbUnit->assertTablesEqual( $oDbSet, $oExceptTable );
    }
    //检验挂件数据
    public function checkPendant($arrDbExcept,$arrInitData)
    {
        $arr['ac_application_spread_content'] = $arrDbExcept;
        $arrs['ac_application_spread_content'] = $arrInitData;
        $oDbUnit = new MyDbUnit($arr,$arrs);
        //	验证数据库
        $oDbSet = $oDbUnit->getConnection()->createQueryTable( 'ac_application_spread_content',
            'select a.image,a.is_delete,a.link,a.recommendation,a.sort_time,a.spread_type_id,a.status,a.title from ac_application_spread_content as a left join ac_application_spread_type as s on a.spread_type_id = s.id where s.is_delete = 0 and s.status = 1 and s.interface_parameter = "indexguajian" and a.status = 1 and a.is_delete = 0 and a.sort_time < "now()" order by a.sort_time desc,a.id desc'
        );
        $oExceptTable = $oDbUnit->getTableSet('ac_application_spread_content');
        return $oDbUnit->assertTablesEqual( $oDbSet, $oExceptTable );


    }

}
