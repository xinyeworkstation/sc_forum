<?php

namespace Home\Controller;

use Think\Controller;
use Extend\Oauth\ThinkOauth;
/**
 * 个人中心
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

    public function ChangePassword()
    {
        if (IS_POST) {
            if($_POST['user_test'] == session('password_id')){
                echo "123";
            }else{
                echo " 1234";
            }
            /*//$user['username']=session('username');//获取登陆后的的用户名称
            $model=M('user');
            $user['password']=$model->where($user)->field('password')->find();
            if($user['password']==I('user_old_password','','md5')){
                $use['password']=I('user_new_password','','md5');//重置的新密码
                if($model->save($use));
            }*/
        }
        if (!IS_POST) {
            $this->display();
        }
    }

    public function email_verify(){
        $id=$this->getRandOnlyId();
        session('password_id',$id);//记录id到session通过邮箱匹配修改密码
        $user['email'] = '975289275@qq.com';
        SendMail($user['email'], "您好，请点击链接修改密码！","您的验证码是:".$id."/n"."打死也不要给别人看到哦！");

        }

    //Author:铜豌豆
    //QQ:309581329
    //Email:bestphper@126.com
    //http://gongwen.sinaapp.com
    //产生一个随机数进行安全验证
    function getRandOnlyId() {
        //新时间截定义,基于世界未日2012-12-21的时间戳。
        $endtime=1356019200;//2012-12-21时间戳
        $curtime=time();//当前时间戳
        $newtime=$curtime-$endtime;//新时间戳
        $rand=rand(0,99);//两位随机
        $all=$rand.$newtime;
        $onlyid=base_convert($all,10,8);//把10进制转为36进制的唯一ID
        return $onlyid;
    }
}
