<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/2/23
 * Time: 下午4:02
 */
namespace App\Repositories\V1;

interface ExampleInterface {
    public function insert();
    public function select();
    public function update();
    public function delete();
}