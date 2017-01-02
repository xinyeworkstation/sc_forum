<?php

namespace Home\Controller;

use Think\Controller;
use Extend\Oauth\ThinkOauth;
/**
 * 用户本地登陆和第三方登陆
 */
class LoginController extends Controller{

    public function index(){
        $this->display();
    }

    public function login(){
        //登陆
        if(IS_POST) {
            $login['Code']=I('Code');
            $login['username'] = I('name');
            $login['password'] = I('password', '', 'md5');
            if($this->check_verify($login['Code'])){
                    $model = M('user');
                    $member = $model->where($login)->find();
                    if ($member) {
                            session('user_id', $member['id']);
                            session('user_name', $member['username']);
                            session('user_level', $member['level']);
                            $success = array(
                            'info' => 'YES'
                         );
                            $this->ajaxReturn($success);//返回前端，用JS跳转
                        }else {
                            $fail = array(
                             'info' => '此用户未被注册！！'
                          );
                             $this->ajaxReturn($fail);//返回前端，用JS跳转
                            }
        }else{
            $fail = array(
                'info' => '验证码错误！'
            );
            $this->ajaxReturn($fail);//返回前端，用JS跳转
        }
    }}


    public function register()
    {
        //注册
        if(IS_POST){
            print_r($_POST);
            $register['username']=I('name');
            $regist['email'] = I('email');//邮箱
            $model=M('user');
            $counts=$model->where($register)->count();//查询此邮箱和用户有没有被注册
            //如果创纪录小于一则未被注册
            if($counts<1){
                    $regist['password'] = I('pass','','md5');//MD5加密密码
                   // $regist['Invitation'] = $_GET['Invitation'];//邀请码
                    $regist['qq'] =I('QQ');//qq
                if($model->add($register)){
                    $success = array(
                        'info' => 'YES'
                    );
                    $this->ajaxReturn($success);//返回前端，用JS跳转
                }else{
                    $fail = array(
                        'info' => '注册失败！'
                    );
                    $this->ajaxReturn($fail);//返回前端，用JS跳转
                }
            }else{
                //如果counts>1返回错误信息！
                $fail = array(
                    'info' => '此邮箱或用户名已被注册'
                );
                $this->ajaxReturn($fail);//返回前端，用JS跳转
            }
        }
    }

public function  email(){

}


//验证码
    public function verify(){
        $Verify = new \Think\Verify();
        $Verify->codeSet = '0123456789';
        $Verify->fontSize = 13;
        $Verify->length = 4;
        $Verify->entry();
    }
    protected function check_verify($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }
}
