<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(App\Models\AcWebModule::class, function (Faker\Generator $faker) {
    return [
        'block_id' => '',//关联AcWebBlock的id
        'module_type' => 26,//位置
        'content_count' => 10,//区块可容纳内容数量
        'orders' => 1,
        'name' => 'testtest',
        'title' => '测试区块',
        'sub_title' => '副标题测试',
    ];
});

$factory->define(App\Models\AcWebBlock::class, function (Faker\Generator $faker) {
    return [
        'page_id' => 10,
        'name' => 'testtest',
        'block_type' => 17,
        'cid' => 70,
        'orders' => 70,
    ];
});
