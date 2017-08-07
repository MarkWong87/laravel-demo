<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/2/23
 * Time: 下午4:27
 */
namespace App\Repositories\V1;

class ExampleImplement implements ExampleInterface   {
    public function insert(){
        return 'this is insert V1 | '.env('APP_KEY');
    }

    public function select(){
        return 'this is select V1 | '.env('APP_KEY');
    }

    public function update(){
        return 'this is update V1 | '.env('APP_KEY');
    }

    public function delete(){
        return 'this is delete V1 | '.env('APP_KEY');
    }
}
