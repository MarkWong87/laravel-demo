<?php
/**
 * Created by PhpStorm.
 * User: markWong
 * Date: 2017/2/23
 * Time: 下午4:27
 */
namespace App\Repositories\V1;

use App\Constants\EsQueryConstants;
use App\Models\AcBangumi;
use App\Models\AcBangumiCount;
use App\Models\AcContentVideo;
use App\Models\AcCounting;
use App\Models\AcWebContent;
use App\Models\AcWebModule;
use App\Models\AcWebTag;
use App\Models\JcContent;
use Elasticsearch\ClientBuilder;

class QueryImplement implements QueryInterface {
    private $prefix = 'api_v2_';
    const IS_FRESH = false;
    const REDIS_CACHE_TIME = 30;

    public function getRank(array $param)
    {
        $maxTryTimes = 6;

        $result = $this->__esSearch($param);

        $count = $result[EsQueryConstants::ES_QUERY_RESULT_TOTAL_KEY];
        $i = 1;
        while ($maxTryTimes > 0 && $param['isForce'] && $param['page'] == 1 && $count < $param['size']) {
            $maxTryTimes--;
            $i++;
            $param['contributeTimeStart'] = $param['contributeTimeStart'] - 24*3600*1000*$i*100;//TODO 需要去掉*100
            $result = $this->__esSearch($param);
            $count = $result[EsQueryConstants::ES_QUERY_RESULT_TOTAL_KEY];
        }

        return $result;
    }

    public function getHotTagsByCid(int $cid, int $size = 10, $isFresh = self::IS_FRESH, array $field = ['*'])
    {
        $redisKey = $this->prefix.'hot_tags_'.$cid;
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null || $isFresh == true) {
            $result = AcWebTag::select($field)->where('cid', $cid)
                ->where('fix', 1)
                ->orderBy('orders', 'desc')
                ->orderBy('video_count', 'desc')
                ->take($size)
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }

        return $result;
    }

    public function getModuleByBlockId(int $blockId, $isFresh = self::IS_FRESH, array $field = ['*'])
    {
        $redisKey = $this->prefix.'module_'.$blockId;
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null  || $isFresh == true) {
            $result = AcWebModule::select($field)->where('block_id', $blockId)
                ->where('is_deleted', 0)->where('status', 0)
                ->orderBy('orders', 'desc')
                ->orderBy('id', 'desc')
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }
        return $result;
    }

    public function getContentByModuleId(array $moduleId, $isFresh = self::IS_FRESH, array $field = ['ac_web_content.*'])
    {
        $redisKey = $this->prefix.'content_'.md5(implode('_', $moduleId));
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null  || $isFresh == true) {
            $result = AcWebContent::select($field)->leftJoin('jc_content', function ($join) {
                $join->on('ac_web_content.media_id', '=', 'jc_content.content_id');
            })
                ->whereIn('ac_web_content.module_id', $moduleId)
                ->where('ac_web_content.is_deleted', 0)
                ->where('ac_web_content.sort_time', '<=', 'now()')
                ->where(function ($query) {
                    $query->where('ac_web_content.media_id', 0)
                        ->orWhereNotIn('ac_web_content.media_type', [0,1])
                        ->orWhere(function ($query2){
                            $query2->whereIn('ac_web_content.media_type', [0,1])
                                ->where('jc_content.status', 2)
                                ->whereIn('jc_content.type_id', [1,3]);
                        });
                })
                ->orderBy('ac_web_content.sort_time', 'desc')
                ->orderBy('ac_web_content.id', 'desc')
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }

        return $result;
    }

    public function getJcContentById(array $mediaId, $isFresh = self::IS_FRESH, array $field = ['*'])
    {
        $redisKey = $this->prefix.'jc_content_'.md5(implode('_', $mediaId));
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null  || $isFresh == true) {
            $result = JcContent::select($field)
                ->whereIn('content_id', $mediaId)
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }
        return $result;
    }

    public function getAcCountingById(array $mediaId, $isFresh = self::IS_FRESH, array $field = ['*'])
    {
        $redisKey = $this->prefix.'ac_counting_'.md5(implode('_', $mediaId));
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null  || $isFresh == true) {
            $result = AcCounting::select($field)
                ->whereIn('id', $mediaId)
                ->where('dataType', 'ac')
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }
        return $result;
    }

    public function getAcContentVideoById(array $mediaId, $isFresh = self::IS_FRESH, array $field = ['*'])
    {
        // TODO: group by content_id ???
        $redisKey = $this->prefix.'ac_content_'.md5(implode('_', $mediaId));
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null  || $isFresh == true) {
            $result = AcContentVideo::select($field)
                ->whereIn('content_id', $mediaId)
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }
        return $result;
    }

    public function getAcBangumiById(array $mediaId, $isFresh = self::IS_FRESH, array $field = ['*'])
    {
        $redisKey = $this->prefix.'ac_bangumi_'.md5(implode('_', $mediaId));
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null  || $isFresh == true) {
            $result = AcBangumi::select($field)
                ->whereIn('id', $mediaId)
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }
            return $result;
    }

    public function getAcBangumiCountById(array $mediaId, $isFresh = self::IS_FRESH, array $field = ['*'])
    {
        $redisKey = $this->prefix.'ac_bangumi_count_'.md5(implode('_', $mediaId));
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null  || $isFresh == true) {
            $result = AcBangumiCount::select($field)
                ->whereIn('id', $mediaId)
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }
            return $result;
    }

    private function __esSearch($param) {
        $hosts = array(env('ES_HOST'));
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        $queryBuilders['index'] = EsQueryConstants::ES_QUERY_INDEX_NAME;
        $queryBuilders['type'] = EsQueryConstants::ES_CONTRIBUTION_QUERY_TYPE_NAME;
        $queryBuilders['from'] = ($param['page'] - 1) * $param['size'];
        $queryBuilders['size'] = $param['size'];

        $must[]['term'][EsQueryConstants::ES_QUERY_FIELD_NAME_STATUS] = 2;//状态(0:草稿;1:审核中;2:审核通过;3:回收站;5:转码中;6:转码失败;7:退回)

        if (!empty($param['channelIds'][0])) {
            $must[]['terms'][EsQueryConstants::ES_QUERY_FIELD_NAME_CHANNEL_ID] = $param['channelIds'];
        } elseif (!empty($param['eliminateChannelIds'][0])) {
            $mustNot[]['terms'][EsQueryConstants::ES_QUERY_FIELD_NAME_CHANNEL_ID] = $param['eliminateChannelIds'];
        }

        if (!empty($param['parentChannelIds'][0])) {
            $must[]['terms'][EsQueryConstants::ES_QUERY_FIELD_NAME_PARENT_CHANNEL_ID] = $param['parentChannelIds'];
        }

        if (!empty($param['tagIds'][0])) {
            $must[]['terms'][EsQueryConstants::ES_QUERY_FIELD_NAME_TAG_LIST_ID] = $param['tagIds'];
        }

        if (!empty($param['isEssense'])) {
            $must[]['term'][EsQueryConstants::ES_QUERY_FIELD_NAME_IS_ESSENSE] = $param['isEssense'];
        }

        if (!empty($param['isRecommend'])) {
            $must[]['term'][EsQueryConstants::ES_QUERY_FIELD_NAME_IS_RECOMMENDED] = $param['isRecommend'];
        }

        if (!empty($param['isTopLevel'])) {
            $must[]['term'][EsQueryConstants::ES_QUERY_FIELD_NAME_IS_TOP_LEVEL] = $param['isTopLevel'];
        }

        if (!empty($param['isArticle'])) {
            $must[]['term'][EsQueryConstants::ES_QUERY_FIELD_NAME_IS_ARTICLE] = $param['isArticle'];
        }

        if ($param['platform'] == 'IOS') {
            if (!empty($param['appStoreVerified'])) {
                $must[]['term'][EsQueryConstants::ES_QUERY_FIELD_NAME_APPSTORE_VERIFIED] = true;
            }
            $must[]['term'][EsQueryConstants::ES_QUERY_FIELD_NAME_DISPLAY] = 0;
        } elseif ($param['platform'] == 'ANDRIOD') {
            $must[]['terms'][EsQueryConstants::ES_QUERY_FIELD_NAME_DISPLAY] = array(0, 2);
        }

        $currentMicrotime = intval(microtime(true)*1000);
        if (!empty($param['contributeTimeStart'])) {
            $param['contributeTimeEnd'] = $param['contributeTimeEnd'] ?? $currentMicrotime;
            $must[]['range'][EsQueryConstants::ES_QUERY_FIELD_NAME_CONTRIBUTE_TIME] = array(
                'gte' => $param['contributeTimeStart'],
                'lt' => $param['contributeTimeEnd']
            );
        }

        if (!empty($param['countTimeStart'])) {
            $param['countTimeEnd'] = $param['countTimeEnd'] ?? $currentMicrotime;
            $must[]['range'][EsQueryConstants::ES_QUERY_FIELD_NAME_LATEST_COUNT_TIME] = array(
                'gte' => $param['countTimeStart'],
                'lt' => $param['countTimeEnd']
            );
        }

        $param['order'] == 1 ? $order = 'desc' : $order = 'asc';
        switch ($param['sort']) {
            case 'pageView':
                $sort[][EsQueryConstants::ES_QUERY_FIELD_NAME_VIEW_COUNT] = $order;
                break;
            case 'comment':
                $sort[][EsQueryConstants::ES_QUERY_FIELD_NAME_COMMENT_COUNT] = $order;
                break;
            case 'danmu':
                $sort[][EsQueryConstants::ES_QUERY_FIELD_NAME_CONTENT_LIST_DANMU_SIZE_LONG_NAME] = $order;
                break;
            case "banana":
                $sort[][EsQueryConstants::ES_QUERY_FIELD_NAME_BANANA_COUNT] = $order;
                //过滤掉typeId为2,4
                if (!empty($param['typeIds'][0])) {
                    if (array_search(2, $param['typeIds']) !== false) {
                        $key = array_search(2, $param['typeIds']);
                        unset($param['typeIds'][$key]);
                    }
                    if (array_search(4, $param['typeIds']) !== false) {
                        $key = array_search(4, $param['typeIds']);
                        unset($param['typeIds'][$key]);
                    }
                    if (empty($param['typeIds'])) $param['typeIds'] = [1, 3];
                } else {
                    $param['typeIds'] = [1, 3];
                }
                break;
            case "active":
                $sort[][EsQueryConstants::ES_QUERY_FIELD_NAME_LATEST_ACTIVE_TIME] = $order;
                break;
            case "favorite":
                $sort[][EsQueryConstants::ES_QUERY_FIELD_NAME_FAVORITE_COUNT] = $order;
                break;
            case "latestComment":
                $mustNot[]['term'][EsQueryConstants::ES_QUERY_FIELD_NAME_COMMENT_COUNT] = 0;
                $sort[][EsQueryConstants::ES_QUERY_FIELD_NAME_LATEST_COMMENT_TIME] = $order;
                break;
            case "latestDanmu":
                $sort[][EsQueryConstants::ES_QUERY_FIELD_NAME_LATEST_DANMU_TIME] = $order;
                break;
            default:
                $sort[][EsQueryConstants::ES_QUERY_FIELD_NAME_ID] = $order;
        }

        if (!empty($param['typeIds'][0])) {
            foreach ($param['typeIds'] as $val) {
                $should[]['term'][EsQueryConstants::ES_QUERY_FIELD_NAME_TYPE_ID] = $val;
            }
        }

        if (!empty($must)) $queryBuilders['body']['query']['filtered']['filter']['bool']['must'] = $must;
        if (!empty($mustNot)) $queryBuilders['body']['query']['filtered']['filter']['bool']['must_not'] = $mustNot;
        if (!empty($should)) $queryBuilders['body']['query']['filtered']['filter']['bool']['should'] = $should;
        if (!empty($sort)) $queryBuilders['body']['sort'] = $sort;

        $response = $client->search($queryBuilders);
        $result[EsQueryConstants::ES_QUERY_RESULT_TOOK_KEY] = $response['took'];
        $result[EsQueryConstants::ES_QUERY_RESULT_TOTAL_KEY] = $response['hits']['total'];
        if ($result[EsQueryConstants::ES_QUERY_RESULT_TOTAL_KEY] > 0) {
            foreach ($response['hits']['hits'] as $val) {
                $result[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY][] = $val['_source'];
            }
        } else {
            $result[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY] = [];
        }

        $result['current_page'] = $param['page'];
        $result['has_next_page'] = ($result[EsQueryConstants::ES_QUERY_RESULT_TOTAL_KEY]/$param['size'] > $param['page']) ? 1 : 0;
        $result['channel_id'] = intval($param['channelIds'][0]);//TODO 不支持数组形式,目前es查询channelid都是单个的,如果是多个需要修改

        return $this->__convertRank($result);
    }

    private function __convertRank($result) {
        $returnList = array();

        foreach ($result[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY] as $value) {
            $tempList = array();
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_ID] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_ID];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_CHANNEL_ID] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_CHANNEL_ID];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_PARENT_CHANNEL_ID] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_PARENT_CHANNEL_ID];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_BANANA_COUNT] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_BANANA_COUNT];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_TITLE] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_TITLE];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_LINK] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_LINK];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_DESCRIPTION] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_DESCRIPTION];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_VIEW_COUNT] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_VIEW_COUNT];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_COVER_IMAGE] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_COVER_IMAGE];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_COMMENT_COUNT] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_COMMENT_COUNT];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_USERNAME] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_USERNAME];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_USER_ID] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_USER_ID];

            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_FAVORITE_COUNT] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_FAVORITE_COUNT];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_CONTRIBUTE_TIME] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_CONTRIBUTE_TIME];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_CHANNEL_PATH] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_CHANNEL_PATH];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_TUDOU_DOMAIN] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_TUDOU_DOMAIN];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_ESSENSE] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_ESSENSE];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_TOP_LEVEL] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_TOP_LEVEL];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_LATEST_COMMENT_TIME] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_LATEST_COMMENT_TIME];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_USER_AVATAR] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_USER_AVATAR];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_ARTICLE] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_ARTICLE];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_BIG_COVER_IMAGE] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_BIG_COVER_IMAGE];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_VIEW_ONLY] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_VIEW_ONLY];
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_RECOMMENDED] = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_IS_RECOMMENDED];

            //视频弹幕数据列表
            $contentList = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_CONTENT_LIST];
            if (!empty($contentList)) {
                foreach ($contentList as $item) {
                    if ($item[EsQueryConstants::ES_QUERY_FIELD_NAME_CONTENT_LIST_INDEX] == 0) {
                        $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_CONTENT_LIST_DANMU_SIZE] = $item[EsQueryConstants::ES_QUERY_FIELD_NAME_CONTENT_LIST_DANMU_SIZE];
                        $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_VIDEO_ID] = $item[EsQueryConstants::ES_QUERY_FIELD_NAME_CONTENT_LIST_ID];
                        $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_CONTENT_LIST_DURATION] = $item[EsQueryConstants::ES_QUERY_FIELD_NAME_CONTENT_LIST_DURATION];
                        break;
                    }
                }
            } else {
                //todo error_log
            }

            //tags数据列表,08.04修改为获取List中包含id和name
            $tagList = $value[EsQueryConstants::ES_QUERY_FIELD_NAME_TAG_LIST];
            if (!empty($tagList)) {
                $tagNameList = array();
                foreach ($tagList as $item) {
                    $tagName = $item[EsQueryConstants::ES_QUERY_FIELD_NAME_TAG_LIST_NAME];
                    if (!empty($tagName)) {
                        $tagNameList[] = $tagName;
                    }
                }
                $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_TAG_LIST] = $tagNameList;
            } else {
                //todo error_log
            }

            //add用户认证信息
            $tempList[EsQueryConstants::ES_QUERY_FIELD_NAME_VERIFIED] = false;//todo 根据userid获取用户认证信息
            $tempList[EsQueryConstants::ES_QUERY_RESULT_FIELD_NAME_VERIFIED_TEXT] = '';//todo 根据userid获取用户认证信息

            ksort($tempList);//TODO 按键值排序,可以优化去掉
            $returnList[] = $tempList;
        }
        $result[EsQueryConstants::ES_QUERY_RESULT_HITS_KEY] = $returnList;
        return $result;
    }

}
