<?php
namespace App\Repositories\V1;

interface PagesInterface {
    public function getBlock(array $data);
    public function getModule($block_id);
}