<?php
namespace App\Repositories\V1;
interface GlobalInterface {
    public function getHotWord($type);
    public function getContent(array $param);
    public function getNavigations($resource);
    public function getPendant(array $data);




}