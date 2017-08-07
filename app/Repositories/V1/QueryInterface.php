<?php
/**
 * Created by PhpStorm.
 * User: markWong
 * Date: 2017/2/23
 * Time: 下午4:02
 */
namespace App\Repositories\V1;

interface QueryInterface {
    public function getRank(array $param);
    public function getHotTagsByCid(int $cid, int $size, $isFresh, array $field);
    public function getModuleByBlockId(int $blockId, $isFresh, array $field);
    public function getContentByModuleId(array $moduleId, $isFresh, array $field);
    public function getJcContentById(array $mediaId, $isFresh, array $field);
    public function getAcCountingById(array $mediaId, $isFresh, array $field);
    public function getAcContentVideoById(array $mediaId, $isFresh, array $field);
    public function getAcBangumiById(array $mediaId, $isFresh, array $field);
    public function getAcBangumiCountById(array $mediaId, $isFresh, array $field);
}