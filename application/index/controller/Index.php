<?php
/**
 * 首页
 */
namespace app\index\controller;

class Index extends Base
{
    public function index()
    {
        return view('index');
    }

    public function test(){
        return $this->fetch('welfare');
    }

    /***************************************************  APIf方法  *********************************************************************/


}
