<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Repositories\V2\ExampleInterface;

use Symfony\Component\HttpFoundation\Cookie;

class ExampleController extends Controller
{
    private $video;
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
        return response($re)->withCookie(new Cookie('foo', 'bar'));
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
