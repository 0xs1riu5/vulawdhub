<?php
namespace app\api\controller;

use core\basic\Controller;

class IndexController extends Controller
{

    public function index()
    {
        json(0, '不存在的API');
    }
}