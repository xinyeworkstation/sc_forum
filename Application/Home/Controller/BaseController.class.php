<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller{
	public function _initialize(){
		 $uid = session('user_id');
        //判断用户是否登陆
        if(!isset($uid ) ) {
        	echo "Login/index";
            redirect(U('Login/index'));
        }
	}
}