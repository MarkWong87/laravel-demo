<?php
/**
 * 单元测试不走框架入口文件,
 * 需要把/Users/User/workspaces/webapi-acfun-cn-2-0/app/Http/Middleware/ErrorLog.php
 * 文件中的常量LUMEN_START注释掉
 */

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class QueryControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

    }
    public function testRank() {

        $responseKeys = ['name', 'url', 'errorid', 'errordesc', 'vdata', 'version', 'allversion'];

//        $request['eliminateChannelIds'] = '';
        $request['channelIds'] = '70';
//        $request['parentChannelIds'] = '';
//        $request['tagIds'] = '';
//        $request['typeIds'] = '1,3';
//        $request['contributeTimeStart'] = '';
//        $request['contributeTimeEnd'] = '';
//        $request['countTimeStart'] = '';
//        $request['countTimeEnd'] = '';
//        $request['sort'] = 'id';
//        $request['order'] = 1;
//        $request['isEssense'] = '';
//        $request['isRecommend'] = '';
//        $request['isTopLevel'] = '';
//        $request['isArticle'] = '';
//        $request['platform'] = '';
//        $request['appStoreVerified'] = '';
//        $request['page'] = 1;
        $request['size'] = 20;
//        $request['isForce'] = true;

        $response = $this->call('GET', self::appUrl('query/rank'), $request);
        $json = $response->getContent();
        $array = json_decode($json, true);
        $this->assertSame($responseKeys, array_keys($array));
        $this->assertEquals($request['size'], count($array['vdata'][0]['webContents']));
    }

    public function testModule() {
        $requestArr = array(
//            ['blockId'=>154,'blockType'=>26,'channelIds'=>70],//轮播图+6小视频
//            ['blockId'=>85,'blockType'=>16,'channelIds'=>70],//编辑推荐
            ['blockId'=>86,'blockType'=>13,'channelIds'=>70],//科技香蕉榜
//            ['blockId'=>93,'blockType'=>17,'channelIds'=>90],//科学技术
//            ['blockId'=>92,'blockType'=>17,'channelIds'=>151],//教程
//            ['blockId'=>89,'blockType'=>17,'channelIds'=>93],//数码
//            ['blockId'=>94,'blockType'=>17,'channelIds'=>122],//汽车
//            ['blockId'=>90,'blockType'=>17,'channelIds'=>149],//广告
//            ['blockId'=>95,'blockType'=>14,'channelIds'=>70],//热门标签+排行榜+名人堂
        );
        foreach ($requestArr as $value) {
            $response = $this->call('GET', self::appUrl('query/module'), $value);
            $json = $response->getContent();
            $array = json_decode($json, true);
            //dd($array['vdata'][0]['webContents']);
            if ($value['blockId'] == 86) {
                $this->assertEquals($array['vdata'][0]['content_count']*2, count($array['vdata'][0]['webContents']['day'])+count($array['vdata'][0]['webContents']['week']));
            } else {
                $this->assertEquals($array['vdata'][0]['content_count'], count($array['vdata'][0]['webContents']));
            }
        }

    }

    public function testHotTags() {
        $old ='{"name":"search","url":"\/query\/hotTags","errorid":0,"errordesc":"","vdata":[{"id":42142,"tag_id":930086,"tag_name":"\u6db2\u538b\u673a","orders":1,"video_count":38,"fix":1,"cid":70,"update_time":"2016-05-30 00:00:00","is_delete":0},{"id":42144,"tag_id":374540,"tag_name":"\u67da\u5b50\u6728\u5b57\u5e55\u7ec4","orders":1,"video_count":1010,"fix":1,"cid":70,"update_time":"2016-05-30 00:00:00","is_delete":0},{"id":42148,"tag_id":8789,"tag_name":"\u79d1\u666e","orders":1,"video_count":351,"fix":1,"cid":70,"update_time":"2016-05-30 00:00:00","is_delete":0},{"id":42151,"tag_id":61640,"tag_name":"\u79d1\u5b66\u5b9e\u9a8c","orders":1,"video_count":19,"fix":1,"cid":70,"update_time":"2016-05-30 00:00:00","is_delete":0},{"id":42159,"tag_id":6243,"tag_name":"\u6559\u7a0b","orders":1,"video_count":715,"fix":1,"cid":70,"update_time":"2016-05-30 00:00:00","is_delete":0}],"version":"v1","allversion":["v1"]}';

        $request['cid'] = 70;
        $request['resource'] = 'mysql';

        $response = $this->call('GET', self::appUrl('query/hotTags'), $request);
        $json = $response->getContent();
        $this->assertSame($old, $json);
    }

    public function tearDown() {}
}