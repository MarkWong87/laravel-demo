<?php
namespace App\Http\Controllers\V1;
use App\Constants\ErrorCode;
use App\Constants\TypeCode;
use App\Repositories\V1\GlobalInterface;
use Illuminate\Http\Request;
use App\Constants\VesionCode;
class GlobalController extends BaseController
{
    private $global;
    public function __construct(GlobalInterface $global)
    {
        $this->global = $global;
    }
    /**
     * 所有数据聚合
     * qiaohuan 2017年3月6
     * @return json
     */
    public function index(Request $request)
    {
       //获取热门搜索词
       $type = $request->get('type','WEB');
       $resource = $request->get('resource');
       $data['type'] = strtoupper($type);
       $data['resource'] = $resource;
       $result =  $this->getGlobal(TypeCode::TYPE_GLOBAL,$data);
       if(isset($result['code'])&&$result['code'] == ErrorCode::ERROR_PROMPT_FAIL){
           $result = [];
           $errorid = ErrorCode::ERROR_PROMPT_FAIL;
           $errordesc = ErrorCode::ERROR_PROMPT_FAIL_DESCRIBE;
       }else{

           $errorid = ErrorCode::ERROR_PROMPT_SUCCESS;
           $errordesc = ErrorCode::ERROR_PROMPT_SUCCESS_DESCRIBE;

       }
        return $this->responseFormat($result, $request->url(),$this->version,'global',$errorid,$errordesc);
    }
    /**
     * 取出热门搜索词
     * qiaohuan 2017年3月6
     * @return json
     */
    public function hotWord(Request $request)
    {
        $type = $request->get('type','WEB');
        $resource = $request->get('resource');

        $data['type'] = strtoupper($type);
        $data['resource'] = $resource;
        $rs =  $this->getGlobal(TypeCode::TYPE_HOTWORD,$data);
        if(isset($rs['code'])&&$rs['code']==ErrorCode::ERROR_PROMPT_FAIL){
            $rs = [];
            $errorid = ErrorCode::ERROR_PROMPT_FAIL;
            $errordesc = ErrorCode::ERROR_PROMPT_FAIL_DESCRIBE;

        }else{
            $errorid = ErrorCode::ERROR_PROMPT_SUCCESS;
            $errordesc = ErrorCode::ERROR_PROMPT_SUCCESS_DESCRIBE;
        }
        return $this->responseFormat($rs, $request->url(),$this->version,'hotword',$errorid,$errordesc);

    }
    /**
     * 获取搜索文本框现有文字
     * qiaohuan 2017年3月6
     * @return json
     */
    public function seaText(Request $request)
    {

        $resource = $request->get('resource');
        $data['resource'] = $resource;
        $textResult =  $this->getGlobal(TypeCode::TYPE_SEATEXT,$data);
        if(isset($textResult['code'])&&$textResult['code']==ErrorCode::ERROR_PROMPT_FAIL){
            $textResult = [];
            $errorid = ErrorCode::ERROR_PROMPT_FAIL;
            $errordesc = ErrorCode::ERROR_PROMPT_FAIL_DESCRIBE;
        }else{
            $errorid = ErrorCode::ERROR_PROMPT_SUCCESS;
            $errordesc = ErrorCode::ERROR_PROMPT_SUCCESS_DESCRIBE;
        }

        return $this->responseFormat($textResult, $request->url(),$this->version,'seatext',$errorid,$errordesc);
    }
    /**
     * 获取banner
     * qiaohuan 2017年3月6
     * @return json
     */
     public function  banner(Request $request)
     {
         $resource = $request->get('resource');
         $data['resource'] = $resource;
         $rs =  $this->getGlobal(TypeCode::TYPE_BANNERS,$data);
         if(isset($rs['code'])&&$rs['code']==ErrorCode::ERROR_PROMPT_FAIL){
             $rs = [];
             $errorid = ErrorCode::ERROR_PROMPT_FAIL;
             $errordesc = ErrorCode::ERROR_PROMPT_FAIL_DESCRIBE;
         }else{
             $errorid = ErrorCode::ERROR_PROMPT_SUCCESS;
             $errordesc = ErrorCode::ERROR_PROMPT_SUCCESS_DESCRIBE;
         }
         return $this->responseFormat($rs, $request->url(),$this->version,'banner',$errorid,$errordesc);

     }
    /**
     * 获取导航
     * qiaohuan 2017年3月6
     * @return json
     */
    public function navigations(Request $request)
    {
        $resource = $request->get('resource');
        $data['resource'] = $resource;
        $rs =  $this->getGlobal(TypeCode::TYPE_NAVIGATIONS,$data);
        if(isset($rs['code'])&&$rs['code']==ErrorCode::ERROR_PROMPT_FAIL){
            $rs = [];
            $errorid = ErrorCode::ERROR_PROMPT_FAIL;
            $errordesc = ErrorCode::ERROR_PROMPT_FAIL_DESCRIBE;
        } else{
            $errorid = ErrorCode::ERROR_PROMPT_SUCCESS;
            $errordesc = ErrorCode::ERROR_PROMPT_SUCCESS_DESCRIBE;
        }
        return $this->responseFormat($rs, $request->url(),$this->version,'navigations',$errorid,$errordesc);
    }
    /**
     * 获取挂件
     * qiaohuan 2017年3月6
     * @return json
     */
    public function pendant(Request $request)
    {

        $this->validate($request, [
            'identifying' => 'string',
            'resource' => 'string',
        ]);
        $data['resource'] = $request->get('resource');
        $data['identifying'] = $request->get('identifying');

        $result =  $this->getGlobal(TypeCode::TYPE_PENDANT,$data);
        if(isset($result['code'])&&$result['code']==ErrorCode::ERROR_PROMPT_FAIL){
            $result = [];
            $errorid = ErrorCode::ERROR_PROMPT_FAIL;
            $errordesc = ErrorCode::ERROR_PROMPT_FAIL_DESCRIBE;
        }else{
            $errorid = ErrorCode::ERROR_PROMPT_SUCCESS;
            $errordesc = ErrorCode::ERROR_PROMPT_SUCCESS_DESCRIBE;
        }
        return $this->responseFormat($result, $request->url(),$this->version,'pendant',$errorid,$errordesc);
    }

    /**
     * 获取所有内容汇聚
     * qiaohuan 2017年3月6
     * @return json
     */
    public function getGlobal($status,$data=[])
    {
        $dt = [];
        if((isset($data['type'])&&($data['type']&&!in_array($data['type'],TypeCode::TYPE_HOTWORD_TYPE)))||(isset($data['resource']))&&($data['resource']&&$data['resource'] !=TypeCode::TYPE_RESOURCE)){

            $dt['code'] = ErrorCode::ERROR_PROMPT_FAIL;
            return $dt;
        }
        if($status == TypeCode::TYPE_HOTWORD||$status==TypeCode::TYPE_GLOBAL){

            $hotWord = $this->global->getHotWord($data);

            if($status == TypeCode::TYPE_HOTWORD){

                return $hotWord;
            }
        }
        if($status == TypeCode::TYPE_SEATEXT||$status==TypeCode::TYPE_GLOBAL) {

            $param['time'] = date("Y-m-d H:i:s", time());
            $param['type'] = TypeCode::TYPE_DEFAULT_WORD;
            $param['resource'] =  $data['resource'];
            $field = ['global_url','global_name'];

            $seaText = $this->global->getContent($param,$field);

            if($status==TypeCode::TYPE_SEATEXT){
                return $seaText;
            }
        }
        if($status == TypeCode::TYPE_BANNERS||$status==TypeCode::TYPE_GLOBAL) {
            $param['time'] = date("Y-m-d H:i:s", time());
            $param['type'] = TypeCode::TYPE_BANNER;
            $param['resource'] =  $data['resource'];
            $field = ['global_url','global_name','global_text','global_id','narrow_img','publish_date','wide_img'];
            $banner = $this->global->getContent($param,$field);
            $banner['publish_date'] = time($banner['publish_date']);

            if($status == TypeCode::TYPE_BANNERS){

                return $banner;
            }
        }
        if($status == TypeCode::TYPE_NAVIGATIONS||$status == TypeCode::TYPE_GLOBAL) {

            $field = ['id','cid','link','media_type','nav_name','orders','parent','status'];
            $navResult = $this->global->getNavigations($data['resource'],$field);
            if($status == TypeCode::TYPE_NAVIGATIONS){
                return $navResult;
            }
        }
        if($status == TypeCode::TYPE_PENDANT||$status == TypeCode::TYPE_GLOBAL) {
            if(isset($data['identifying']) && $data['identifying']){
                $data['type'] = $data['identifying'];
            }else{
                $data['type'] = 'indexguajian';
            }
            $field = ['ac_application_spread_content.id','ac_application_spread_content.image',
                'ac_application_spread_content.is_delete','ac_application_spread_content.link',
                'ac_application_spread_content.recommendation', 'ac_application_spread_content.sort_time',
                'ac_application_spread_content.spread_type_id','ac_application_spread_content.status',
                'ac_application_spread_content.title'];
            $pendant = $this->global->getPendant($data,$field);
            if($status == TypeCode::TYPE_PENDANT){
                return $pendant;
            }
        }
        if($status == TypeCode::TYPE_GLOBAL){
            $result['hot'] = $hotWord;
            $result['defaultkw'] = $seaText;
            $result['banner'] = $banner;
            $result['navigations'] = $navResult;
            $result['pendant'] = $pendant;
        }
        return $result;
    }
}

