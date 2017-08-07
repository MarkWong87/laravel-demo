<?php
/**
 * Created by PhpStorm.
 * User: markWong
 * Date: 2017/4/6
 * Time: 下午6:13
 */

namespace App\Repositories\V1;


use App\Models\JcAdvertising;
use App\Models\JcContent;

class ArticleImplement implements ArticleInterface {
    private $prefix = 'api_v2_';
    const IS_FRESH = false;
    const REDIS_CACHE_TIME = 30;

    public function getAd(int $id, $isFresh = self::IS_FRESH, array $field = ['*'])
    {
        $redisKey = $this->prefix.'jc_advertising_a_'.$id;
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        $currentTime = date('Y-m-d');
        if ($result === null || $isFresh == true) {
            $result = JcAdvertising::select($field)->where('advertising_id', $id)
                ->where('is_enabled', 1)
                ->where('start_time', '<=', $currentTime)
                ->where('end_time', '>=', $currentTime)
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }
        return $result;
    }

    public function getList(int $channelId, $isFresh = self::IS_FRESH, array $field = ['content_id', 'title', 'comments', 'views', 'user_id', 'username', 'description', 'update_at'])
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $redisKey = $this->prefix.'jc_content_a_'.$channelId.'_'.$page;
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null || $isFresh == true) {
            $result = JcContent::select($field)->where('channel_id', $channelId)
                ->whereIn('type_id', [1, 2, 3, 4])
                ->where('status', 2)
                ->orderBy('content_id', 'desc')
                ->paginate(12);
            $result->appends('channelId', $channelId);
            app('phpredis')->set($redisKey, json_encode($result->toArray()), self::REDIS_CACHE_TIME);
        }
        return $result;
    }

    public function getRecommend(int $channelId, $isFresh = self::IS_FRESH, array $field = ['content_id', 'title', 'sort_date'])
    {
        $redisKey = $this->prefix.'jc_content_a_recommend_'.$channelId;
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null || $isFresh == true) {
            $result = JcContent::select($field)->where('channel_id', $channelId)
                ->where('status', 2)
                ->where('is_recommend', 1)
                ->whereIn('type_id', [1, 3])
                ->orderBy('sort_date', 'desc')
                ->take(10)
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }
        return $result;
    }

    public function getHot(int $channelId, $isFresh = self::IS_FRESH, array $field = ['jc_content.content_id', 'jc_content.title', 'jc_content_count.views_day'])
    {
        $redisKey = $this->prefix.'jc_content_a_hot_'.$channelId;
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        $today = date("Y-m-d");
        if ($result === null || $isFresh == true) {
            $result = JcContent::select($field)
                ->leftJoin('jc_content_count', function ($join) {
                    $join->on('jc_content.content_id', '=', 'jc_content_count.content_id');
                })
                ->where('jc_content.channel_id', $channelId)
                ->whereIn('jc_content.type_id', [1, 3])
                ->where('jc_content.status', 2)
                ->where('jc_content.update_at', '>', $today)
                ->orderBy('jc_content_count.views_day', 'desc')
                ->orderBy('jc_content_count.content_id', 'desc')
                ->take(10)
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }
        return $result;
    }

    public function getReply(int $channelId, $isFresh, array $field = ['content_id', 'title', 'last_feedback'])
    {
        $redisKey = $this->prefix.'jc_content_a_reply_'.$channelId;
        $result = app('phpredis')->get($redisKey);
        $result = json_decode($result, true);
        if ($result === null || $isFresh == true) {
            $result = JcContent::select($field)->where('channel_id', $channelId)
                ->where('status', 2)
                ->whereIn('type_id', [1, 3])
                ->orderBy('last_feedback', 'desc')
                ->take(10)
                ->get()
                ->toArray();
            app('phpredis')->set($redisKey, json_encode($result), self::REDIS_CACHE_TIME);
        }
        return $result;
    }
}