<?php

namespace Home\Controller;

use Think\Controller;
use Extend\Oauth\ThinkOauth;
/**
 * 用户本地登陆和第三方登陆
 */
class PersonController extends Controller
{

    public function index()
    {
        $this->display();
    }

    public function PersonMessage()
    {
        //$user['username']=session('username');//获取登陆后的的用户名称
        $model = M('user');//实例化USER对象
        $user['username'] = 'yangyang';
        if (!IS_POST) {
            $person_message = $model->where($user)->find();//获取用户基本信息
            $this->assign('P_message', $person_message);
            $this->display();
        }

        if (IS_POST) {
            $user['username'] = I('user_name');
            $user['email'] = I('user_email');
            $user['qq'] = I('user_QQ');
            $user['address'] = I('user_address');
            $user['sex'] = I('user_sex');
            $user['birth'] = I('user_birth');
            $user['profession'] = I('user_profession');
            $user['oneself'] = I('user_oneself');
            print_r($user);
            //通过id为条件，更新数据
            if ($model->where('id=8')->save($user)) {
                $this->success('成功插入！');
            } else {
                $this->error('成功插入！');
            }

        }

    }

    public function BasicData()
    {
        $model = M('user');//实例化USER对象
        $user['username'] = 'yangyang';
        if (!IS_POST) {
            $person_message = $model->where($user)->find();//获取用户基本信息
            $this->assign('P_message', $person_message);
            $this->display();
        }


    }

    public function ChangePassword(){
        if(IS_POST){
            print_r($_POST);
            /*//$user['username']=session('username');//获取登陆后的的用户名称
            $model=M('user');
            $user['password']=$model->where($user)->field('password')->find();
            if($user['password']==I('user_old_password','','md5')){
                $use['password']=I('user_new_password','','md5');//重置的新密码
                if($model->save($use));
            }*/
        }
        if(!IS_POST){
            $this->display();
        }

    }
}
