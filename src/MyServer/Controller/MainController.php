<?php
namespace MyServer\Controller;

use MyServer\Core\Request;
use MyServer\Core\Controller;

class MainController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->getDb();
        print_r($request);
    }
}
