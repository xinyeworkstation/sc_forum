<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller {
    //登陆主页
    public function index(){
        $this->display();
    }
    //登陆验证
    public function login(){
        $member = M('user');
        $username =I('username');
        //$password =I('password','','md5');
        $password =I('password');
        $code = I('verify','','strtolower');
        //验证验证码是否正确
        if(!($this->check_verify($code))){
            $this->error('验证码错误');
        }
        //验证账号密码是否正确
        $user = $member->where(array('username'=>$username,'password'=>$password))->find();
        if(!$user) {
            $this->error('账号或密码错误 :(') ;
        }else{
            //如果用户信息正确，验证账户是否为站长，是站长，可以登录后台
            if(!($user['level'] == 0)){
                $this->error('此账号账号不能登录后台管理 :(') ;
            }
        }

		session('adminId',$user['id']);
        session('adminName',$user['username']);
        $this->success("登陆成功",U('Index/index'));
		
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

    public function logout(){
        session('adminId',null);
        session('adminName',null);
        redirect(U('Login/index'));
    }
}