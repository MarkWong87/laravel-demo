<?php
namespace App\Repositories\V1;
use App\Models\AcApplicationSpreadContent;
use App\Models\AcApplicationSpreadType;
use App\Models\JcHotWord;
use App\Models\AcGlobalA;
use App\Models\AcWebNav;
use App\Constants\TypeCode;
class GlobalImplement  implements GlobalInterface {
    public function __construct() {
        $this->keyrefix = 'global_';
    }
    /**
     * 获取搜索关键词
     * qiaohuan 2017年3月6
     * @return json
     */
    public function getHotWord($data,$field = '*'){
        $rs = app('phpredis')->get($this->keyrefix.'hot_'.$data['type']);

        $result = json_decode($rs);

        if($result === null||$data['resource'] == 'mysql'){
            $result =  JcHotWord::select($field)
                ->where('type', $data['type'])
                ->orderBy('orderby', 'asc')
                ->get()
                ->toArray();
           $res = json_encode($result);
           app('phpredis')->set($this->keyrefix.'hot_'.$data['type'],$res,60*20);
        }

        return $result;
    }
    /**
     * 获取banner跟搜索框中默认内容
     * qiaohuan 2017年3月6
     * @return json
     */
    public function getContent(array $param,$field=[])
    {

        $rs = app('phpredis')->get($this->keyrefix.'content_'.$param['type']);

        $result = json_decode($rs,true);

        if($result === null||$param['resource'] == 'mysql'){

            $result =  AcGlobalA::select($field)
                ->where('type', $param['type'])
                ->where('publish_date', '<=',$param['time'])
                ->where('status',TypeCode::TYPE_A_DISPLAY_STATUS)//0:显示 1:隐藏
                ->orderBy('publish_date', 'desc')
                ->orderBy('global_id', 'desc')
                ->first();
                app('phpredis')->set($this->keyrefix.'content_'.$param['type'],json_encode($result),60*10);
        }
        return $result;
    }
    /**
     * 获取导航
     * qiaohuan 2017年3月6
     * @return json
     */
    public function getNavigations($resource,$field=[])
    {
        //返回树状结构
        $navResult = app('phpredis')->get($this->keyrefix.'navigations_tree');
        $res = json_decode($navResult);
        if($navResult === null ||$resource=='mysql'){
            $rs = AcWebNav::select($field)
            ->where('is_delete', TypeCode::TYPE_NOT_DELETE)//1:是 0:否
            ->where('status', TypeCode::TYPE_DISPLAY_STATUS)//1:显示 0:隐藏
            ->orderBy('orders', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
            $res =  $this->resolveTree($rs,0);
            $tree = json_encode($res);
            app('phpredis')->set($this->keyrefix.'navigations_tree',$tree,60*60*8);

       }
        return $res;

    }
    /**
     * 循环递归
     * qiaohuan 2017年3月6
     * @return json
     */
    public function resolveTree($result,$pid)
    {
        $tree = [];
        foreach($result as $k => $v){
            if($v['parent'] == $pid){
                $v['children'] = $this->resolveTree($result,$v['id']);
                if(empty($v['children'])){
                    $v['children'] = [];
                }
                $tree[] = $v;
            }
        }
        return $tree;
    }
    /**
     * 获取挂件
     * qiaohuan 2017年3月6
     * @return json
     */
    public function getPendant(array $data,$field=[])
    {
        $res = app('phpredis')->get($this->keyrefix.'pendant_'.$data['type']);
        $result = json_decode($res,true);
        if($result === null||$data['resource']=='mysql') {
            $result = AcApplicationSpreadContent::select($field)
                ->leftJoin('ac_application_spread_type', function ($join) {
                    $join->on('ac_application_spread_content.spread_type_id', '=', 'ac_application_spread_type.id');
                })
                ->where('ac_application_spread_type.is_delete', TypeCode::TYPE_NOT_DELETE)
                ->where('ac_application_spread_type.status', TypeCode::TYPE_B_DISPLAY_STATUS)
                ->where('ac_application_spread_type.interface_parameter', $data['type'])
                ->where('ac_application_spread_content.status', TypeCode::TYPE_DISPLAY_STATUS)
                ->where('ac_application_spread_content.is_delete', TypeCode::TYPE_NOT_DELETE)
                ->where('ac_application_spread_content.sort_time', '<', 'now()')
                ->orderBy('ac_application_spread_content.sort_time', 'desc')
                ->orderBy('ac_application_spread_content.id', 'desc')
                ->orderBy('ac_application_spread_type.sortId', 'desc')
                ->get()
                ->toArray();
            $re = json_encode($result);
            app('phpredis')->set($this->keyrefix.'pendant_'.$data['type'],$re,60*10);

        }
        return $result;

    }

}
