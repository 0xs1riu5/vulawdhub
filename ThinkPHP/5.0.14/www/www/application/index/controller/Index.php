<?php
namespace app\index\controller;
use think\Db;

class Index
{
    public function index()
    {
        $password = input("get.password/a");
        db('user')->where(['id'=>1])->update(['password'=>$password]);
        return "ThinkPHP SQL Test.";
    }
}

