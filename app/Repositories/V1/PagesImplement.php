<?php
namespace App\Repositories\V1;
use App\Models\AcWebModule;
use App\Models\AcWebPage;
use App\Models\AcWebBlock;
use App\Constants\TypeCode;
class PagesImplement implements PagesInterface {
    public function __construct() {
        $this->keyrefix = 'page_';
    }
    /**
     * 获取所有区块
     * qiaohuan 2017年3月6
     * @return json
     */
     public function getBlock(array $data, $field = ['ac_web_block.*']){
           $res = app('phpredis')->get($this->keyrefix.'block_'.$data['cid']);
           $newResult = json_decode($res,true);

           if(($newResult===null||$data['resource'] == 'mysql')) {
               $newResult = [];
               $result = AcWebBlock::select($field)->leftJoin('ac_web_page', function ($join) {
                   $join->on('ac_web_block.page_id', '=', 'ac_web_page.id');
               })
               ->where('ac_web_page.cid', $data['cid'])
               ->where('ac_web_page.status', TypeCode::TYPE_C_DISPLAY_STATUS)
               ->where('is_deleted', TypeCode::TYPE_NOT_DELETE)
               ->where('ac_web_block.status', TypeCode::TYPE_C_DISPLAY_STATUS)
               ->orderBy('ac_web_block.orders', 'desc')
               ->orderBy('ac_web_block.id', 'desc')
               ->get()
               ->toArray();
           foreach ($result as $k => $v) {
               if($v['cid']==null){
                   $v['cid'] = '';
               }
               $v['module'] = $this->getModule($v['id'],$data['resource']);
               $newResult[] = $v;
           }
           $re = json_encode($newResult);
           app('phpredis')->set($this->keyrefix . 'block_' . $data['cid'], $re,60*60*9);
       }
       return $newResult;
   }
   /*
    * 根据区块id的获取模块类型
    * qiaohuan 2017年3月13
    * return json
    */
    public function getModule($block_id){
            $result = AcWebModule::select('module_type')
                ->where('block_id', $block_id)
                ->where('is_deleted', TypeCode::TYPE_NOT_DELETE)
                ->where('status', TypeCode::TYPE_C_DISPLAY_STATUS)
                ->orderBy('orders', 'desc')
                ->get()
                ->toArray();
        $module = array_column($result, 'module_type');
        return $module;
    }



}
