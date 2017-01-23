<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller{
	public function _initialize(){
		 $uid = session('user_id');
        //判断用户是否登陆
        if(isset($uid) ) {
            $success = array(
                'info' => 'YES'
            );
            //$this->success('你成功了！');
            $this->ajaxReturn($success);//返回前端，用JS跳转
        }
	}
}