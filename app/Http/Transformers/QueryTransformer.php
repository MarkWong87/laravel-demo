<?php
/**
 * Created by PhpStorm.
 * User: markWong
 * Date: 2017/3/7
 * Time: 下午2:16
 */
namespace App\Http\Transformers;

use App\Models\AcWebContent;
use App\Models\AcWebModule;
use League\Fractal\TransformerAbstract;

class QueryTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [];

    public function transform(AcWebModule $acWebModule)
    {
        return [
            $acWebModule
        ];
    }

}