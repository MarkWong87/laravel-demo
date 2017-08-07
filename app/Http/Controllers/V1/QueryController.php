<?php

namespace App\Http\Controllers\V1;
use App\Constants\ErrorCode;
use App\Constants\EsQueryConstants;
use App\Constants\TypeCode;
use App\Models\AcUser;
use App\Repositories\V1\QueryInterface;
use Illuminate\Http\Request;
use App\Constants\VesionCode;

class QueryController extends BaseController
{
    private $query;
    private $minPage = 1;
    private $maxPage = 1000;
    private $minSize = 8;
    private $maxSize = 500;
    private $es_module_type = array(1,2,3);

    public function __construct(QueryInterface $query) {
        $this->query = $query;
    }

    /**
     * 排行榜接口
     *
     * @Get("/query/rank")
     * @Versions({"v1"})
     * @Parameters({
     *     @Parameter("eliminateChannelIds", description="不需要搜索的子频道id"),
     *     @Parameter("channelIds", description="需要搜索的子频道id"),
     *     @Parameter("parentChannelIds", description="父频道id"),
     *     @Parameter("tagIds", description="标签id"),
     *     @Parameter("typeIds", description="typeIds 1:普通,2:福利,3:原创,4:下榜,香蕉榜默认1,3"),
     *     @Parameter("contributeTimeStart", description="投稿时间-开始"),
     *     @Parameter("contributeTimeEnd", description="投稿时间-结束"),
     *     @Parameter("countTimeStart", description="最后统计时间-开始???"),
     *     @Parameter("countTimeEnd", description="最后统计时间-结束???"),
     *     @Parameter("sort", description="排序字段", default="id"),
     *     @Parameter("order", description="排序方式", default="DESC"),
     *     @Parameter("isEssense", description="是否本质???"),
     *     @Parameter("isRecommend", description="是否推荐"),
     *     @Parameter("isTopLevel", description="是否置顶"),
     *     @Parameter("isArticle", description="是否文章"),
     *     @Parameter("platform", description="???"),
     *     @Parameter("appStoreVerified", description="???"),
     *     @Parameter("page", description="页码,最大值1000", default=1),
     *     @Parameter("size", description="每页数据,最大值50", default=10)
     * })
     *
     */
    public function rank(Request $request) {
        $this->validate($request, [
            'eliminateChannelIds' => 'string',
            'channelIds' => 'string',
            'parentChannelIds' => 'string',
            'tagIds' => 'string',
            'typeIds' => 'string',
            'contributeTimeStart' => 'string',
            'contributeTimeEnd' => 'string',
            'countTimeStart' => 'string',
            'countTimeEnd' => 'string',
            'sort' => 'string',
            'order' => 'integer',
            'isEssense' => 'string',
            'isRecommend' => 'string',
            'isTopLevel' => 'string',
            'isArticle' => 'string',
            'platform' => 'string',
            'appStoreVerified' => 'string',
            'page' => 'integer',
            'size' => 'integer',
            'isForce' => 'string',
        ]);
        $param['eliminateChannelIds'] = explode(',', $request->get('eliminateChannelIds'));
        $param['channelIds'] = explode(',', $request->get('channelIds'));
        $param['parentChannelIds'] = explode(',', $request->get('parentChannelIds'));
        $param['tagIds'] = explode(',', $request->get('tagIds'));
        $param['typeIds'] = explode(',', $request->get('typeIds', '1,3'));
        $param['contributeTimeStart'] = $request->get('contributeTimeStart');
        $param['contributeTimeEnd'] = $request->get('contributeTimeEnd');
        $param['countTimeStart'] = $request->get('countTimeStart');
        $param['countTimeEnd'] = $request->get('countTimeEnd');
        $param['sort'] = $request->get('sort', 'id');
        $param['order'] = $request->get('order', 1);
        $param['isEssense'] = $request->get('isEssense');
        $param['isRecommend'] = $request->get('isRecommend');
        $param['isTopLevel'] = $request->get('isTopLevel');
        $param['isArticle'] = $request->get('isArticle');
        $param['platform'] = strtoupper($request->get('platform'));
        $param['appStoreVerified'] = $request->get('appStoreVerified');
        $param['page'] = $request->get('page', 1);
        $param['size'] = $request->get('size');
        $param['isForce'] = $request->get('isForce', true);

        if ($param['page'] > $this->maxPage) $param['page'] = $this->maxPage;
        if ($param['page'] <= $this->minPage) $param['page'] = $this->minPage;
        if ($param['size'] > $this->maxSize) $param['size'] = $this->maxSize;
        if ($param['size'] <= $this->minSize) $param['size'] = $this->minSize;
        if ($param['page'] * $param['size'] >= 10000) {
            return $this->responseFormat([], $request->getPathInfo(), $this->version);
        }

        $queryRank = $this->query->getRank($param);

        $queryRank = $this->__compositeRank($queryRank);

        return $this->responseFormat($queryRank, $request->getPathInfo(), $this->version);
    }

    public function getRank(Request $request) {
        $this->validate($request, [
            'eliminateChannelIds' => 'string',
            'channelIds' => 'integer',
            'parentChannelIds' => 'integer',
            'tagIds' => 'string',
            'typeIds' => 'string',
            'contributeTimeStart' => 'integer',
            'contributeTimeEnd' => 'integer',
            'countTimeStart' => 'integer',
            'countTimeEnd' => 'integer',
            'sort' => 'string',
            'order' => 'integer',
            'isEssense' => 'string',
            'isRecommend' => 'string',
            'isTopLevel' => 'string',
            'isArticle' => 'string',
            'platform' => 'string',
            'appStoreVerified' => 'string',
            'page' => 'integer',
            'size' => 'integer',
            'isForce' => 'boolean',
        ]);
        $param['eliminateChannelIds'] = explode(',', $request->get('eliminateChannelIds'));
        $param['channelIds'] = explode(',', $request->get('channelIds'));
        $param['parentChannelIds'] = explode(',', $request->get('parentChannelIds'));
        $param['tagIds'] = explode(',', $request->get('tagIds'));
        $param['typeIds'] = explode(',', $request->get('typeIds', '1,3'));
        $param['contributeTimeStart'] = $request->get('contributeTimeStart');
        $param['contributeTimeEnd'] = $request->get('contributeTimeEnd');
        $param['countTimeStart'] = $request->get('countTimeStart');
        $param['countTimeEnd'] = $request->get('countTimeEnd');
        $param['sort'] = $request->get('sort', 'id');
        $param['order'] = $request->get('order', 1);
        $param['isEssense'] = $request->get('isEssense');
        $param['isRecommend'] = $request->get('isRecommend');
        $param['isTopLevel'] = $request->get('isTopLevel');
        $param['isArticle'] = $request->get('isArticle');
        $param['platform'] = strtoupper($request->get('platform'));
        $param['appStoreVerified'] = $request->get('appStoreVerified');
        $param['page'] = $request->get('page', 1);
        $param['size'] = $request->get('size');
        $param['isForce'] = $request->get('isForce', true);

        if ($param['page'] > $this->maxPage) $param['page'] = $this->maxPage;
        if ($param['page'] <= $this->minPage) $param['page'] = $this->minPage;
        if ($param['size'] > $this->maxSize) $param['size'] = $this->maxSize;
        if ($param['size'] <= $this->minSize) $param['size'] = $this->minSize;
        if ($param['page'] * $param['size'] >= 10000) {
            return $this->responseFormat([], $request->getPathInfo(), $this->version);
        }

        $queryRank = $this->query->getRank($param);

        return $queryRank;
    }

    /**
     * 热门标签
     * cid当前频道id,查询10个fix=1固定的热门标签
     * @Get("/query/tag")
     * @Version({"v1"})
     * @Parameters({
     *     @Parameter("cid", description="频道id", default="当前频道id"),
     * })
     */
    public function hotTags(Request $request) {
        $this->validate($request, [
            'cid' => 'required|integer',
            'num' => 'integer',
            'resource' => 'string',
        ]);

        $cid = $request->get('cid');
        $flag = $request->get('resource')==TypeCode::TYPE_RESOURCE;
        $size  = $request->get('num', 10);
        $queryTags = $this->query->getHotTagsByCid($cid, $size, $flag);
        return $this->responseFormat($queryTags, $request->getPathInfo(),$this->version);
    }

    /**
     * 获取区块信息
     * @Get("/query/module")
     * @Version({"v1"})
     * @Parameters({
     *     @Parameter("blockId", description="区块id"),
     * })
     */
    public function module(Request $request) {
        //DB::connection()->enableQueryLog();//打印sql
        $this->validate($request, [
            'blockId' => 'required|integer',
            'blockType' => 'required|integer',
            'channelIds' => 'integer',
            'resource' => 'string',
        ]);
        $blockId = $request->get('blockId');
        $flag = $request->get('resource')==TypeCode::TYPE_RESOURCE;
        $queryModules = $this->query->getModuleByBlockId($blockId, $flag);
        $moduleIdArr = array_column($queryModules, 'id');

        $queryContents = $this->query->getContentByModuleId($moduleIdArr, $flag);

        $mediaIdArr = array();
        $queryContentsGroupBymoduleId = array();
        foreach ($queryContents as $value) {
            if ($value['media_id'] == 0) {
                continue;
            }
            $mediaIdArr['acContentVideoIds'][] = $value['media_id'];
            if ($value['media_type'] == 0 || $value['media_type'] == 1) {
                $mediaIdArr['jcContentIds'][] = $value['media_id'];
            } elseif ($value['media_type'] == 2) {
                $mediaIdArr['acBangumiIds'][] = $value['media_id'];
            }
            $queryContentsGroupBymoduleId[$value['module_id']][] = $value;
        }

        $webContents = $this->__getWebContents($mediaIdArr);

        $queryModules = $this->__compositeModules($queryModules, $queryContentsGroupBymoduleId, $webContents);

        $queryModules = $this->__fillWebContents($queryModules, $request);

        //$sql = DB::getQueryLog();
        //dd($sql);

        return $this->responseFormat($queryModules, $request->getPathInfo(),$this->version);
    }


    /**
     * 转换成驼峰格式
     * @param $acWebContent
     * @return array
     */
    private function __toCamelCase($acWebContent) {
        $result = array();

        $result['creatorId'] = $acWebContent['creator_id'];
        $result['id'] = $acWebContent['id'];
        $result['image'] = $acWebContent['image']; //配图
        $result['isCrown'] = $acWebContent['is_crown']; //是否是皇冠：0 不是；1 是
        $result['isDeleted'] = $acWebContent['is_deleted']; //是否已被删除：1 删除；0 未删除 [todo]
        $result['link'] = $acWebContent['link']; //跳转链接
        $result['mediaId'] = $acWebContent['media_id']; //媒资ID
        $result['mediaType'] = $acWebContent['media_type']; //媒资类型：0 视频；1 文章；2 番剧；3 合辑；4 UP主
        $result['moduleId'] = $acWebContent['module_id']; //所属模块的Id
        $result['releaseDate'] = $acWebContent['release_date']; //发布时间
        $result['smallImage'] = $acWebContent['small_image']; //内容配图－小图
        $result['sort'] = $acWebContent['sort']; //排序字段, 逐渐废弃sortTime[todo]
        $result['sortTime'] = $acWebContent['sort_time']; //排序时间[todo]
        $result['subTitle'] = $acWebContent['sub_title']; //副标题
        $result['title'] = $acWebContent['title']; //标题
        $result['updaterId'] = $acWebContent['updater_id'];

        /*
        foreach ($acWebContent as $key => $value) {
            $key = ucwords(str_replace('_', ' ', $key));
            $key = str_replace(' ','',lcfirst($key));
            $result[$key] = $value;
        }
*/

        return $result;
    }
    /**
     * 组合webcontents信息
     * @param $acWebContent
     * @param $jcContent
     * @param $acContentVideo
     * @return array
     */
    private function __compositeFields($newContent, $jcContent, $acContentVideo) {
        if (!empty($jcContent)) {
            $newContent['userId'] = $jcContent['user_id'];
            $newContent['userName'] = $jcContent['username'];
            $newContent['views'] = $jcContent['views']; //总访问数
            $newContent['comments'] = $jcContent['comments']; //总评论数
            $newContent['danmakuSize'] = $jcContent['danmaku_size']; //弹幕数
            $newContent['stows'] = $jcContent['stows']; //总收藏数
            $newContent['time'] = $jcContent['time']; //视频总时长
            $newContent['contentUpdateAt'] = $jcContent['sort_date']; //排序日期 [todo]
        } else {
            $newContent['userId'] = 0;
            $newContent['userName'] = '';
            $newContent['views'] = 0; //总访问数
            $newContent['comments'] = 0; //总评论数
            $newContent['danmakuSize'] = 0; //弹幕数
            $newContent['stows'] = 0; //总收藏数
            $newContent['time'] = 0; //视频总时长
            $newContent['contentUpdateAt'] = time(); //排序日期
        }
        if (!empty($acContentVideo)) {
            $newContent['videoId'] = $acContentVideo['video_id'];
        } else {
            $newContent['videoId'] = 0;
        }

        return $newContent;
    }

    /**
     * 获取mediaid对应的webcontents信息
     * @param $mediaIdArr
     * @return array
     */
    private function __getWebContents($mediaIdArr) {
        $webContents = array();

        if (isset($mediaIdArr['jcContentIds']) && count($mediaIdArr['jcContentIds']) > 0) {
            $webContents['queryJcContent'] = $this->query->getJcContentById($mediaIdArr['jcContentIds']);
            $webContents['queryAcCounting'] = $this->query->getAcCountingById($mediaIdArr['jcContentIds']);
        }

        if (isset($mediaIdArr['acContentVideoIds']) && count($mediaIdArr['acContentVideoIds']) > 0) {
            $webContents['queryAcContentVideo'] = $this->query->getAcContentVideoById($mediaIdArr['acContentVideoIds']);
        }

        if (isset($mediaIdArr['acBangumiIds']) && count($mediaIdArr['acBangumiIds']) > 0) {
            $webContents['queryAcBangumi'] = $this->query->getAcBangumiById($mediaIdArr['acBangumiIds']);
            $webContents['queryAcBangumiCount'] = $this->query->getAcBangumiCountById($mediaIdArr['acBangumiIds']);
        }

        return $webContents;
    }

    /**
     * 组合modules信息
     * @param $queryModules
     * @param $queryContentsGroupBymoduleId
     * @param $webContents
     * @return mixed
     */
    private function __compositeModules($queryModules, $queryContentsGroupBymoduleId, $webContents) {

        foreach ($queryModules as &$value) {
            $tempWebContents = isset($queryContentsGroupBymoduleId[$value['id']]) ? $queryContentsGroupBymoduleId[$value['id']] : array();

            $newContent = array();
            if (in_array($value['module_type'],$this->es_module_type))
            {
                $count = count($tempWebContents) > $value['content_count']*2 ? $value['content_count']*2 : count($tempWebContents);
            }else
            {
                $count = count($tempWebContents) > $value['content_count'] ? $value['content_count'] : count($tempWebContents);
            }
            for ($i = 0; $i < $count; $i++) {
                $mediaId = $tempWebContents[$i]['media_id'];
                $newContent[$i] = $this->__toCamelCase($tempWebContents[$i]);
                if ($tempWebContents[$i]['media_type'] == 0 || $tempWebContents[$i]['media_type'] == 1) {
                    $tempJcContent = current(array_filter($webContents['queryJcContent'], function($t) use ($mediaId) { return $t['content_id'] == $mediaId; }));
                    $tempAcContentVideo = current(array_filter($webContents['queryAcContentVideo'], function($t) use ($mediaId) { return $t['content_id'] == $mediaId; }));
                    $newContent[$i] = $this->__compositeFields($newContent[$i], $tempJcContent, $tempAcContentVideo);
                    $tempAcCounting = current(array_filter($webContents['queryAcCounting'], function($t) use ($mediaId) { return $t['id'] == $mediaId; }));
                    if (!empty($tempAcCounting) && $tempAcCounting['views'] > 0) {
                        $newContent[$i]['views'] = $tempAcCounting['views'];
                    }
                } elseif ($tempWebContents[$i]['media_type'] == 2) {
                    $tempAcBangumi = current(array_filter($webContents['queryAcBangumi'], function($t) use ($mediaId) { return $t['id'] == $mediaId; }));
                    $tempAcBangumiCount = current(array_filter($webContents['queryAcBangumiCount'], function($t) use ($mediaId) { return $t['id'] == $mediaId; }));
                    if (!empty($tempAcBangumi)) {
                        $newContent[$i]['contentUpdateAt'] = $tempAcBangumi['last_update_time'];
                    } else {
                        $newContent[$i]['contentUpdateAt'] = time()-24*60*60;
                    }
                    if (!empty($tempAcBangumiCount)) {
                        $newContent[$i]['stows'] = $tempAcBangumiCount['stow'];
                    } else {
                        $newContent[$i]['stows'] = 0;
                    }
                } else {
                    $newContent[$i]['userId'] = 0;
                    $newContent[$i]['userName'] = '';
                    $newContent[$i]['views'] = 0;
                    $newContent[$i]['comments'] = 0;
                    $newContent[$i]['danmakuSize'] = 0;
                    $newContent[$i]['stows'] = 0;
                    $newContent[$i]['time'] = 0;
                    $newContent[$i]['contentUpdateAt'] = time();
                }
                ksort($newContent[$i]);
            }
            $value['webContents'] = $newContent;
        }
        return $queryModules;
    }

    /**
     * @desc 修改换一换返回数据格式,与初始化返回的格式类似,方便前端逻辑处理
     * @param $rank
     * @return array
     */
    private function __compositeRank($rank) {
        $newRank = array();
        $newRank[0]['channel_id'] = $rank['channel_id'];
        $newRank[0]['current_page'] = $rank['current_page'];
        if ($rank['current_page'] == $this->maxPage) {
            $newRank[0]['has_next_page'] = 0;
        } else {
            $newRank[0]['has_next_page'] = $rank['has_next_page'];
        }

        $newRank[0]['webContents'] = $rank[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY];

        return $newRank;
    }

    /**
     * @desc 填充webContents数据
     * 频道页:
     * moduleType=17 是排行榜，有日榜和周榜，当前页面频道，按照pagaView排序;
     * moduleType=25 是香蕉榜，有日榜和周榜，当前页面频道，按照banana排序;
     * moduleType=26 是换一换，当前区块频道，按照latestDanmu排序;
     * 首页:
     * blockType in(4,5,18) && moduleType==17 取当前区块频道,按照latestDanmu排序;
     * moduleType==8 换一换,取当前区块频道,按照latestComment排序;
     *
     * @param $queryModules
     * @param $request
     * @return mixed
     */
    private function __fillWebContents($queryModules, $request) {
        $blockType = $request->get('blockType');
        foreach ($queryModules as &$value) {
            //轮播图+6小视频和二级页猴子推荐需要和香蕉榜去重,香蕉榜出现的视频不能再出现
            if (in_array($value['module_type'],$this->es_module_type))
            {
                $currentMicrotime = intval(microtime(true)*1000);
                $contributeTimeStart = $currentMicrotime - 24*3600*1000;

                $parentChannelIds = $request->get('parentChannelIds', $request->get('channelIds'));
                $request->query->add(array(
                    'sort' => 'banana',
                    'size' => $value['content_count'],
                    'channelIds' => '',
                    'parentChannelIds' => $parentChannelIds,
                    'contributeTimeStart' => $contributeTimeStart,
                    'contributeTimeEnd' => $currentMicrotime,
                ));
                $dayRankList = $this->getRank($request);

                $contributeTimeStart = $currentMicrotime - 7*24*3600*1000;
                $request->query->add(array('contributeTimeStart'=>$contributeTimeStart));
                $weekRankList = $this->getRank($request);

                if (!empty($value['webContents'])) {
                    $hits = array_merge($dayRankList[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY], $weekRankList[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY]);
                    foreach ($hits as $item) {
                        $videoId = isset($item[EsQueryConstants::ES_QUERY_FIELD_NAME_VIDEO_ID]) ? $item[EsQueryConstants::ES_QUERY_FIELD_NAME_VIDEO_ID] : 0;
                        $mediaId = isset($item[EsQueryConstants::ES_QUERY_FIELD_NAME_ID]) ? $item[EsQueryConstants::ES_QUERY_FIELD_NAME_ID] : 0;
                        $filterContent = array_filter($value['webContents'], function($v) use ($videoId,$mediaId) {
                            if (isset($v['videoId'])) {
                                return $v['videoId'] == $videoId;
                            } else {
                                return $v['mediaId'] == $mediaId;
                            }
                        });
                        //unset($value['webContents'][key($filterContent)]);
                        if (key($filterContent) !== null) {
                            array_splice($value['webContents'], key($filterContent), 1);
                        }
                    }
                    $value['webContents'] = array_slice($value['webContents'],0,$value['content_count']);

                } else {
                    return $this->responseFormat([], $request->getPathInfo(), $this->version, 'query', ErrorCode::ERROR_PROMPT_FAIL, ErrorCode::ERROR_PROMPT_FAIL_DESCRIBE);
                }
            }

            //香蕉榜窄版
            if ($value['module_type'] == 25) {
                $currentMicrotime = intval(microtime(true)*1000);
                $contributeTimeStart = $currentMicrotime - 24*3600*1000;

                $parentChannelIds = $request->get('parentChannelIds', $request->get('channelIds'));
                $request->query->add(array(
                    'sort' => 'banana',
                    'size' => $value['content_count'],
                    'channelIds' => '',
                    'parentChannelIds' => $parentChannelIds,
                    'contributeTimeStart' => $contributeTimeStart,
                    'contributeTimeEnd' => $currentMicrotime,
                ));
                $rankList = $this->getRank($request);
                $value['webContents']['day'] =$rankList[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY];

                $contributeTimeStart = $currentMicrotime - 7*24*3600*1000;
                $request->query->add(array('contributeTimeStart'=>$contributeTimeStart, 'contributeTimeEnd'=>$currentMicrotime));
                $rankList = $this->getRank($request);
                $value['webContents']['week'] =$rankList[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY];
            }

            //二级页右侧栏
            //if ($blockType == 14) { //TODO
            //热门标签
            if ($value['module_type'] == 4) {
                $cid = intval($request->get('parentChannelIds', $request->get('channelIds')));
                $queryTags = $this->query->getHotTagsByCid($cid);
                $value['webContents'] = $queryTags;
            }

            //排行榜
            if ($value['module_type'] == 17) {
                $currentMicrotime = intval(microtime(true)*1000);
                $contributeTimeStart = $currentMicrotime - 24*3600*1000;

                $parentChannelIds = $request->get('parentChannelIds', $request->get('channelIds'));
                $request->query->add(array(
                    'sort' => 'pageView',
                    'size' => $value['content_count'],
                    'channelIds' => '',
                    'parentChannelIds' => $parentChannelIds,
                    'contributeTimeStart' => $contributeTimeStart,
                    'contributeTimeEnd' => $currentMicrotime,
                ));
                $rankList = $this->getRank($request);
                $value['webContents']['day'] =$rankList[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY];

                $currentMicrotime = intval(microtime(true)*1000);
                $contributeTimeStart = $currentMicrotime - 7*24*3600*1000;
                $request->query->add(array('contributeTimeStart'=>$contributeTimeStart, 'contributeTimeEnd'=>$currentMicrotime));
                $rankList = $this->getRank($request);
                $value['webContents']['week'] =$rankList[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY];
            }

            //名人堂添加头像
            if ($value['module_type'] == 23) {
                foreach ($value['webContents'] as &$item) {
                    $userImg = AcUser::select(['user_img', 'verified'])->where('user_id', $item['userId'])->first();
                    $item['userCover'] = $userImg['user_img'];
                    $item['verified'] = $userImg['verified'];
                }
            }
            //}

            //本区动态
            if ($value['module_type'] == 26 || (in_array($blockType, [4, 5, 18]) && $value['module_type'] == 17)) {
                $channelIds = $request->get('channelIds');
                $request->query->add(array(
                    'sort' => 'latestDanmu',
                    'size' => $value['content_count'],
                    'channelIds' => $channelIds,
                ));
                $rankList = $this->getRank($request);
                if (isset($value['webContents']) && !empty($value['webContents'])) {//如果该webContents里有编辑推荐的数据则和es数据进行去重整合
                    $value['webContents'] = $this->__arrayMerge($value['webContents'], $rankList[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY], $value['content_count']);
                } else {
                    $value['webContents'] =$rankList[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY];
                }
                $value['current_page'] = $rankList['current_page'];
                $value['has_next_page'] = $rankList['has_next_page'];
                $value['channel_id'] = $rankList['channel_id'];
            }

            //首页按最后评论排序
            if ($value['module_type'] == 8) {
                $channelIds = $request->get('channelIds');
                $request->query->add(array(
                    'sort' => 'latestComment',
                    'size' => $value['content_count'],
                    'channelIds' => $channelIds,
                ));
                $rankList = $this->getRank($request);
                if (isset($value['webContents']) && !empty($value['webContents'])) {//如果该webContents里有编辑推荐的数据则和es数据进行去重整合
                    $value['webContents'] = $this->__arrayMerge($value['webContents'], $rankList[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY], $value['content_count']);
                } else {
                    $value['webContents'] =$rankList[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY];
                }
                $value['current_page'] = $rankList['current_page'];
                $value['has_next_page'] = $rankList['has_next_page'];
                $value['channel_id'] = $rankList['channel_id'];
            }

            ksort($value);//TODO 按键值排序,可以优化去掉
        }
        return $queryModules;
    }

    private function __arrayMerge($webContents, $rankList, $size) {
        $newWebContents = array();
        $i = 0;
        foreach ($webContents as $webContent) {
            $newWebContents[$i]['banana_count'] = 0;
            $newWebContents[$i]['big_cover_image'] = '';
            $newWebContents[$i]['channel_id'] = 0;
            $newWebContents[$i]['channel_path'] = 'v';
            $newWebContents[$i]['comment_count'] = $webContent['comments'];
            $newWebContents[$i]['contribute_time'] = strtotime($webContent['contentUpdateAt'])*1000;
            $newWebContents[$i]['cover_image'] = $webContent['image'];
            $newWebContents[$i]['danmu_size'] = $webContent['danmakuSize'];
            $newWebContents[$i]['description'] = '';
            $newWebContents[$i]['duration'] = $webContent['time'];
            $newWebContents[$i]['favorite_count'] = 0;
            $newWebContents[$i]['id'] = $webContent['mediaId'];
            $newWebContents[$i]['is_article'] = false;
            $newWebContents[$i]['is_essense'] = false;
            $newWebContents[$i]['is_recommended'] = $webContent['isCrown']==1;
            $newWebContents[$i]['is_top_level'] = false;
            $newWebContents[$i]['is_tudou_domain'] = false;
            $newWebContents[$i]['is_view_only'] = false;
            $newWebContents[$i]['latest_comment_time'] = strtotime($webContent['contentUpdateAt'])*1000;
            $newWebContents[$i]['link'] = $webContent['link'];
            $newWebContents[$i]['parent_channel_id'] = 0;
            $newWebContents[$i]['tag_list'] = [];
            $newWebContents[$i]['title'] = $webContent['title'];
            $newWebContents[$i]['user_avatar'] = '';
            $newWebContents[$i]['user_id'] = $webContent['userId'];
            $newWebContents[$i]['username'] = $webContent['userName'];
            $newWebContents[$i]['verified'] = false;
            $newWebContents[$i]['verifiedText'] = '';
            $newWebContents[$i]['video_id'] = $webContent['videoId'];
            $newWebContents[$i]['view_count'] = $webContent['views'];

            $videoId = $webContent['videoId'];
            $filterContent = array_filter($rankList, function($v) use ($videoId) { return $v['video_id'] == $videoId; });
            //unset($rankList[key($filterContent)]);
            if (key($filterContent) !== null) {
                array_splice($rankList, key($filterContent), 1);
            }
            $i++;
        }
        $return = array_merge($newWebContents, $rankList);
        return array_slice($return, 0, $size);
    }



}
