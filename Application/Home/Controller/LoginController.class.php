<?php

namespace Home\Controller;

use Think\Controller;
use Extend\Oauth\ThinkOauth;
/**
 * 用户本地登陆和第三方登陆
 */
class LoginController extends Controller{

    public function index()
    {
        //注册
        if($_GET['user'] && $_GET['email']){
            $register['username']=I('get.user');
            $regist['email'] = I('get.email');//邮箱
            $model=M('user');
            $counts=$model->where($register)->count();//查询此邮箱和用户有没有被注册
            //如果创纪录小于一则未被注册
            if($counts<1){
                    $regist['password'] = I('get.pass','','md5');//MD5加密密码
                    // $regist['Invitation'] = $_GET['Invitation'];//邀请码
                    $regist['qq'] =I('get.qq');//qq
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



        //登陆
        if($_GET['user'] && $_GET['verify']) {
            if ($this->check_verify($_GET['verify'])) {
                $login['username'] = I('get.user');
                $login['password'] = I('get.pass', '', 'md5');
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
                } else {
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
        }





        if(IS_POST) {
            /*$regist['username'] = $_POST['name'];//用户名
            $regist['password'] = $_POST['pass'];//密码
            $regist['email'] = $_POST['email'];//邮箱
            $regist['Invitation'] = $_POST['Invitation'];//邀请码
            $regist['qq'] = $_POST['qq'];*/
               echo "123";

            $this->ajaxReturn($data);

        }
        $this->display();


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
