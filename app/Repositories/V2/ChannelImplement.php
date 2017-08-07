<?php
namespace App\Repositories\V2;
use App\Constants\TypeCode;
use App\Models\AcWebPage;
class ChannelImplement  implements ChannelInterface {
    /**
     * 获取频道内容
     * qiaohuan 2017年3月6
     * @return json
     */
    public function getChannel($field = []){
        $result = AcWebPage::select($field)
            ->where('status', TypeCode::TYPE_C_DISPLAY_STATUS)
            ->where('site_id', 1)
            ->get()
            ->toArray();
        return $result;
    }

}
