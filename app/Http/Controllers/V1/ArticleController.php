<?php
/**
 * Created by PhpStorm.
 * User: markWong
 * Date: 2017/4/6
 * Time: 下午6:05
 */

namespace App\Http\Controllers\V1;


use App\Constants\TypeCode;
use Illuminate\Http\Request;
use App\Repositories\V1\ArticleInterface;

class ArticleController extends BaseController
{
    private $article;

    public function __construct(ArticleInterface $article) {
        $this->article = $article;
    }

    public function getAd(Request $request) {
        $this->validate($request, [
            'id' => 'required|integer',
            'resource' => 'string',
        ]);

        $id = $request->get('id');
        $flag = $request->get('resource')==TypeCode::TYPE_RESOURCE;
        $ad = $this->article->getAd($id, $flag);
        return $this->responseFormat($ad, $request->getPathInfo(),$this->version);
    }

    public function getList(Request $request) {
        $this->validate($request, [
            'channelId' => 'required|integer',
            'resource' => 'string',
        ]);
        $channelId = $request->get('channelId');
        $flag = $request->get('resource')==TypeCode::TYPE_RESOURCE;
        $list = $this->article->getList($channelId, $flag);
        return $this->responseFormat($list, $request->getPathInfo(),$this->version);
    }

    public function getRecommend(Request $request) {
        $this->validate($request, [
            'channelId' => 'required|integer',
            'resource' => 'string',
        ]);
        $channelId = $request->get('channelId');
        $flag = $request->get('resource')==TypeCode::TYPE_RESOURCE;
        $list = $this->article->getRecommend($channelId, $flag);
        return $this->responseFormat($list, $request->getPathInfo(),$this->version);
    }

    public function getHot(Request $request) {
        $this->validate($request, [
            'channelId' => 'required|integer',
            'resource' => 'string',
        ]);
        $channelId = $request->get('channelId');
        $flag = $request->get('resource')==TypeCode::TYPE_RESOURCE;
        $list = $this->article->getHot($channelId, $flag);
        return $this->responseFormat($list, $request->getPathInfo(),$this->version);
    }

    public function getReply(Request $request) {
        $this->validate($request, [
            'channelId' => 'required|integer',
            'resource' => 'string',
        ]);
        $channelId = $request->get('channelId');
        $flag = $request->get('resource')==TypeCode::TYPE_RESOURCE;
        $list = $this->article->getReply($channelId, $flag);
        return $this->responseFormat($list, $request->getPathInfo(),$this->version);

    }
}