<?php
namespace App\Http\Controllers\V1;
use App\Constants\ErrorCode;
use App\Constants\TypeCode;
use App\Repositories\V1\PagesInterface;
use Illuminate\Http\Request;
use App\Constants\VesionCode;
class PagesController extends BaseController
{
    private $pages;

    public function __construct(PagesInterface $pages) {
        $this->pages = $pages;
    }
    /**
     * 取出指定页面所有区块数据
     * qiaohuan 2017年3月6
     * @return json
     */
    public function  index(Request $request)
    {
        $blockId = $request->get('cid');
        $resource = $request->get('resource');
        if(($blockId && !is_numeric($blockId))||($resource && $resource!=TypeCode::TYPE_RESOURCE)){
            $blockResult = [];
            $errorid = ErrorCode::ERROR_PROMPT_FAIL;
            $errordesc = ErrorCode::ERROR_PROMPT_FAIL_DESCRIBE;
        }else{
            $dt['cid'] = $blockId;
            $dt['resource'] = $resource;
            $field = ['ac_web_block.id','ac_web_block.color','ac_web_block.block_type',
                'ac_web_block.is_deleted','ac_web_block.name','ac_web_block.cid',
                'ac_web_block.orders','ac_web_block.page_id','ac_web_block.status','ac_web_block.style'];
            $blockResult = $this->pages->getBlock($dt,$field);

            $errorid = ErrorCode::ERROR_PROMPT_SUCCESS;
            $errordesc = ErrorCode::ERROR_PROMPT_SUCCESS_DESCRIBE;
        }
        //返回数据
        return $this->responseFormat($blockResult, $request->url(),$this->version,'pages',$errorid,$errordesc);
    }
}


?>