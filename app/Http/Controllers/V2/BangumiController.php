<?php
namespace App\Http\Controllers\V2;
use Illuminate\Http\Request;
use App\Constants\ErrorCode;
use App\Constants\VersionCode;
use App\Repositories\V2\BangumiInterface;
class BangumiController extends BaseController
{

	public $page_fields = array("ac_bangumi"=>array(
											"id",'title','cover','intro','year','month','week','status'
											,'views','stows','last_video_id AS lastVideoId','comments'
											,'allow_danmaku AS allowDanmaku','display_new AS displayNew'
											,'display_web AS displayWeb','display_mobile AS displayMobile'
											,'last_update_time AS lastUpdateTime','video_count AS videoCount'
											,'type AS type','cover_horizontal AS coverHorizontal'
											,'cover_vertical AS coverVertical','visible_level AS visibleLevel')
								);
    public function __construct(BangumiInterface  $bangumi) 
	{
        $this->bangumi = $bangumi;
    }

    /**
     * 获取所有频道
     * qiaohuan 2017年3月6
     * @return json
     */
    public function page(Request $request)
    {
		$page_fields = array(
						'resource'	 => 'string',
						'tagIds'     => 'string',
						'type'		 => 'integer',
						'displayIos' => 'integer',
						'isWeb'      => 'integer',
						'isNew'      => 'integer',
						'isIndex'    => 'integer',
						'isUnion'    => 'integer',
						'pageNo'     => 'integer',
						'pagesize'   => 'integer',
						'sort'		 => 'integer',
						);
//(tagIdArray, bangumiTypeArray, con.getWeek(), con.getDisplayNew(), con.getDisplayIos(), con.getIsWeb(), con.getSort(), con.getPageSize(), con.getPageNo(),con.getVisibleLevel());
		$this->validate($request,$page_fields);
        $params = $request->only(array_keys($page_fields));
		$fields = $this->_mergeFields($this->page_fields);
		$res     = $this->bangumi->getList($params,$fields);
		return $this->responseFormat($res, $request->url(),$this->version,'channel',ErrorCode::ERROR_PROMPT_SUCCESS,'',VersionCode::VERSION_CHANNEL);


    }
	/**
	 * 对单表或者连表查询的字段进行整合
	 * $fields  array()  待整合字段  二维数组 表名－>具体字段
	 * return   array() 
	**/
	private function _mergeFields($fields)
	{
		$data = array();
		if(empty($fields))
		{
			return   $data;
		}
		foreach($fields as $table_name=>$field_arr)
		{
			foreach($field_arr as $field)
			{
				$data[]=$table_name.".".$field;
			}
		}	
		return $data ;
	}
}
