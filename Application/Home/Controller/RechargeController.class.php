<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

class RechargeController extends Controller{
    public function index(){
        $user=M('user');
        session('username','shuying');
        $where['username']=session('username');
        $user=$user->where($where)->find();
        $user['headimg']='/'.$user['headimg'];
        $work=M('work');
        $wh['user_id']=$user['id'];
        $workc=$work->where($wh)->count();
        $love=M('favorite');
        $whe['user_id']=$user['id'];
        $lovec=$love->where($wh)->count();
        $this->assign('lovec',$lovec);
        $this->assign('workc',$workc);
        $this->assign('user',$user);
        $this->display();
    } 
}
