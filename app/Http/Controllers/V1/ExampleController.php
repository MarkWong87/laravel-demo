<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\V1\ExampleInterface;
trait TestTrait
{
    public $name;

    public function setName()
    {
        $this->name = "mabo";
        $this->testTr();
    }
}


class ExampleController extends Controller
{
    private $video;
    use TestTrait;



    public function index($phpinfo) {
        $this->setName();
        var_dump($this->name);
        if ($phpinfo == 'phpinfo') echo phpinfo();
    }

    public function testTr()
    {
        echo "test trait";
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ExampleInterface $video)
    {
        $this->video = $video;
    }

    public function insert() {
        $re = $this->video->insert();
        return $re;
    }

    public function select() {
        $re = $this->video->select();
        $re = User::find(1)->children()->get();
        return response($re);
    }

    public function update() {
        $re = $this->video->update();
        return response($re)->header('X-Header-One', 'update');
    }

    public function delete() {
        $re = $this->video->delete();
        return response($re)->header('X-Header-One', 'delete');
    }
}
