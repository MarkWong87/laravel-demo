<?php
namespace App\Http\Controllers\V1;
use App\Constants\ErrorCode;
use App\Constants\VersionCode;
use Illuminate\Http\Request;
use App\Repositories\V1\ChannelInterface;
class ChannelController extends BaseController
{


    private $channel;
    public function __construct(ChannelInterface $channel) {
        $this->channel = $channel;
    }

    /**
     * 获取所有频道
     * qiaohuan 2017年3月6
     * @return json
     */
    public function index(Request $request)
    {
        $field = ['id','name','cid'];
        $res =  $this->channel->getChannel($field);
        return $this->responseFormat($res, $request->url(),$this->version,'channel',ErrorCode::ERROR_PROMPT_SUCCESS,'',VersionCode::VERSION_CHANNEL);
    }

}
