<?php
/**
 * Created by PhpStorm.
 * User: markWong
 * Date: 2017/2/23
 * Time: 下午4:02
 */
namespace App\Repositories\V1;

interface ArticleInterface {
    public function getAd(int $id, $isFresh, array $field);
    public function getList(int $channelId, $isFresh, array $field);
    public function getRecommend(int $channelId, $isFresh, array $field);
    public function getHot(int $channelId, $isFresh, array $field);
    public function getReply(int $channelId, $isFresh, array $field);
}