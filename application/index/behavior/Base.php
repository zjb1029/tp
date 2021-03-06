<?php
/**
 * 行为基类
 * Created by PhpStorm.
 * User: bin
 * Date: 2019/3/30
 * Time: 10:04
 */

namespace app\index\behavior;


use think\Request;
use think\Session;
class Base
{

    /**
     * ajax请求输出结构
     * @var array
     */
    protected $result = [
        'code'=>400,
        'msg'=>'',
        'data'=>[]
    ];

    /**
     * 是否接口请求
     * @var bool
     */
    protected $isApi = false;

    /**
     * 是否登录
     * @var bool
     */
    protected $loginState = false;

    /**
     * 请求信息
     * @var Request|null
     */
    protected $request = null;

    /**
     * 全局状态码
     * @var array
     */
    protected $status = [];

    /**
     * 全局用户详情
     * @var array
     */
    protected $userInfo = [];

    /**
     * 页码
     * @var int
     */
    protected $page = 1;

    /**
     * 请求量
     * @var int
     */
    protected $size = 10;


    public function __construct(Request $request = null)
    {

        $this->request = $request::instance();//请求信息赋值

        $this->isApi = $this->request->isAjax();//判断是否为AJAX请求

        $status = require_once (APP_PATH.DS.'status.php');//引入状态码文件

        $this->setStatus($status);//初始化状态码

        if(Session::has('user')){
            $this->setLoginState(true);
            $this->setUserInfo(Session::get('user'));
        }

        $this->setPageSize();//设置页码和请求量

    }

    /**
     * 是否登录
     */
    public function isLogin($api = false){
        if($this->isLoginState()){
            return true;
        }
        if($api){//api则输出JSON
            $this->result['msg'] = '请先登录！';
            $this->output();
        }else{//页面则跳转至登录
            redirect('Sessions/login');
        }
    }


    /**
     * 输出
     */
    protected function output(){
        if(empty($this->result['msg'])){
            if($this->result['code'] == 200){
                $this->result['msg'] = $this->status[$this->result['code']];
            }else{
                $this->result['msg'] = isset($this->status[$this->result['error_code']]) ? $this->status[$this->result['error_code']] : '请求成功';
            }
        }
        return json($this->result)->send();
    }

    protected function setPageSize(){
        if($this->request->has('page')){
            $this->page = $this->request->param('page');
        }
        if($this->request->has('size')){
            $this->size = $this->request->param('size');
        }
    }

    /**
     * @return bool
     */
    public function isLoginState()
    {
        return $this->loginState;
    }

    /**
     * @param bool $loginState
     */
    public function setLoginState($loginState)
    {
        $this->loginState = $loginState;
    }

    /**
     * @return array
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * @param array $userInfo
     */
    public function setUserInfo($userInfo)
    {
        $this->userInfo = $userInfo;
    }


    /**
     * @return array
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param array $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

}