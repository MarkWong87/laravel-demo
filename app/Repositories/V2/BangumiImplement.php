<?php
/**
 *番剧底层实现类
 *@auth  Dingning@acfun.tv
 *date   2017-04-06
 *
**/
namespace App\Repositories\V2;
use Illuminate\Support\Facades\DB;
class BangumiImplement  implements  BangumiInterface
 {
	 const MAX_PAGESIZE = 100;
	 const PAGESIZE     = 20;
     const REDIS_MIN    = 60;
     const REDIS_HOUR   = 3600;
	 //	完全不可见
	 const DISPLAY_WEB_INVISIBLE = 0;
	 //	完全不可见
	 const DISPLAY_MOBILE_INVISIBLE = 0;
	 //	显示在新番榜单
	 const DISPLAY_NEW_ON = 1;
	 //	不显示在新番榜单
	 const DISPLAY_NEW_OFF = 0;
    /**
     * 获取番剧列表数据
	 * $field  array() 需要返回字段
	 * $query  array() 传入参数条件
	 * $cache  bool    是否穿过缓存查数据库
	 * return  array()
     *
	 */
    public function getList($params,$fields="*",$cache=true)
	{
		if(isset($params['resource']) && $params['resource']=='mysql')
		{
			$cache = false;
		}
		//根据参数生成查询条件
		$where     = $this->_setWhere($params);
		//分页参数
		$pagesize  = 0<$params['pagesize'] && $params['pagesize']<=self::MAX_PAGESIZE?$params['pagesize']:self::PAGESIZE;
		//根据参数中的sort 生成排序方式
		$sort      = $this->_setSort($params['sort']);
		//生成缓存key
		$redis_key = $this->_setRedisKey($params);
		$res = json_decode(app('phpredis')->get($redis_key),true);
		if(($res===null||!$cache)) 
		{
			//如果有tagIds则需要链表查询
			if(isset($params['tagIds']))
			{
				$tag_id  = explode(",",$params['tagIds']);
				foreach($tag_id  as  &$oneTag)
				{
					$oneTag = intval($oneTag);
				}
				$query   = DB::table('ac_bangumi')->join('ac_bangumi_tag','ac_bangumi.id','=','ac_bangumi_tag.bangumi_id')->select($fields);
				if(count($tag_id)>1)
				{
					$query = $query->whereIn("ac_bangumi_tag.tag_id",$tag_id)
									->groupBy('ac_bangumi_tag.bangumi_id')
									->havingRaw('count(ac_bangumi_tag.tag_id) >='.count($tag_id))
									->where($where);
				}else{
					$where[] = array("ac_bangumi_tag.tag_id",$tag_id[0]);
					$query   = $query->where($where);
				}
			}else{
				$query    = DB::table('ac_bangumi')
								->select($fields)
								->where($where);
			}
			//根据返回的排序方式组装多个  orderBy
            foreach($sort as $order_arr)
			{
				  $order = !empty($order_arr[1])?$order_arr[1]:"asc";
				  $query = $query->orderBy($order_arr[0],$order);
			}
			//如果sort为  11-14则需要链表ac_bangumi_count  进行排序
			if($params['sort']>10  && $params['sort']<15)
			{
				$query = $query->join('ac_bangumi_count','ac_bangumi.id','=','ac_bangumi_count.id');
			}
			$res  = $query->paginate($pagesize)->toArray();
			app('phpredis')->set($redis_key, json_encode($res),60*60);
		}
		return $res;
	}

	
	/**
	 *  根据请求参数 整合成where查询条件
	 *  $request		array()  http请求数据 
	 *  return    array()
	 *
	**/

					//	'tagIds'     => 'string',
					//	'type'		 => 'integer',
					//	'displayIos' => 'integer',
					//	'isWeb'      => 'integer',
					//	'isNew'      => 'integer',
					//	'isIndex'    => 'integer',//是否是首页请求
					//	'isUnion'    => 'integer',
					//	'pageNo'     => 'integer',
					//	'pagesize'   => 'integer',
					//	'sort'		 => 'integer',
	private function _setWhere($params)
	{
		$where = array();
		if(isset($params['type']) && !empty($params['type']))
		{
			$where[] = array("ac_bangumi.type",$params['type']);
		}
		if(isset($params['displayIos']) && !empty($params['displayIos']))
		{
			$where[] = array("ac_bangumi.display_ios",$params['displayIos']);
		}
		if($params['isWeb']!=null && $params['isWeb']==1)
		{
			$where[] = array('ac_bangumi.display_web','<>',self::DISPLAY_WEB_INVISIBLE);
		}else{
			$where[] = array('ac_bangumi.display_mobile','<>',self::DISPLAY_MOBILE_INVISIBLE);
		}
		if(isset($params['isNew']) && !empty($params['isNew']))
		{
			$display = $params['isNew']==1?self::DISPLAY_NEW_ON:self::DISPLAY_NEW_OFF;
			$where[] = array("ac_bangumi.display_new",$display);
		}
		return $where;
	}

	/**
	 * 根据 class_name + function_name + 参数序列化生成缓存key
	 * $param    __CLASS__
	 * $param    __FUNCTION__
	 * $param    func_get_args()
	 * return    string
	**/
	private  function _setRedisKey()
	{
		return md5(__CLASS__.__FUNCTION__.serialize(func_get_args()));
	}
	/**
	 *  根据参数中的sort 设定对应的 排序方式
	 *  $param  sort   int  排序值
	 *  return  array()
	**/
	private  function _setSort($sort)
	{
		$sort = $sort>0?$sort:self::SORT_NUMBER;
		$sort_str = array();
		switch($sort)
		{
				case 1 : $sort_str[] = array("ac_bangumi.last_update_time", "desc");break;
				case 2 : $sort_str[] = array("ac_bangumi.last_update_time");break;
				case 3 : $sort_str[] = array("ac_bangumi.year" ,'desc');
						 $sort_str[] = array("ac_bangumi.month", "desc");break;
				case 4 : $sort_str[] = array("ac_bangumi.year");
						 $sort_str[] = array("ac_bangumi.month"); break;
				case 5 : $sort_str[] = array("ac_bangumi.id", "desc");break;
				case 6 : $sort_str[] = array("ac_bangumi.id");break;
				case 7 : $sort_str[] = array("ac_bangumi.comments","desc");break;
				case 8 : $sort_str[] = array("ac_bangumi.comments");break;
				case 11: $sort_str[] = array("ac_bangumi_count.views","desc");break;
				case 12: $sort_str[] = array("ac_bangumi_count.views");break;
				case 13: $sort_str[] = array("ac_bangumi_count.stow", "desc");break;
				case 14: $sort_str[] = array("ac_bangumi_count.stow");break;
				default: $sort_str[] = array("ac_bangumi.last_update_time", "desc");break;
		}
		return $sort_str;

	}
}
