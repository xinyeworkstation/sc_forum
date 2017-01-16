<?php

namespace Home\Controller;

use Think\Controller;
use Extend\Oauth\ThinkOauth;
/**
 * 用户本地登陆和第三方登陆
 */
class LoginController extends Controller
{

    public function index()
    {
        $this->display();
    }

    public function login()
    {
        //登陆
        if (IS_POST) {
            $login['Code'] = I('Code');
            $login['username'] = I('name');
            $login['password'] = I('password', '', 'md5');
            if ($this->check_verify($login['Code'])) {
                $model = M('user');
                $member = $model->where($login)->find();
                if ($member) {
                    session('headimg',$member['headimg']);
                    session('user_id', $member['id']);
                    session('user_name', $member['username']);
                    session('user_level', $member['level']);
                    $success = array(
                        'info' => 'YES'
                    );
                    //$this->success('你成功了！');
                    $this->ajaxReturn($success);//返回前端，用JS跳转
                } else {
                    $fail = array(
                        'info' => '账号或密码错误！'
                    );
                    $this->ajaxReturn($fail);//返回前端，用JS跳转
                }
            } else {
                $fail = array(
                    'info' => '验证码错误！'
                );
                //$this->error('验证码错误！');
                $this->ajaxReturn($fail);//返回前端，用JS跳转
            }

        }
    }


    public function register()
    {
        //注册
        if (IS_POST) {
            $register1['username'] = I('name');
            $register2['email'] = I('email');//邮箱

            $model = M('user');
            $counts = $model->where($register1)->count();
            $counts1 = $model->where($register2)->count();//查询此邮箱和用户有没有被注册
            //如果创纪录小于一则未被注册
            if ($counts < 1 && $counts1 <1) {
                $register['username'] = I('name');
                $register['email'] = I('email');//邮箱
                $register['password'] = I('password', '', 'md5');//MD5加密密码
                // $regist['Invitation'] = $_GET['Invitation'];//邀请码
                $register['qq'] = I('QQ');//qq
                if ($model->add($register)) {
                    $success = array(
                        'info' => 'YES'
                    );
                    $this->ajaxReturn($success);//返回前端，用JS跳转
                } else {
                    $fail = array(
                        'info' => '注册失败！'
                    );
                    $this->ajaxReturn($fail);//返回前端，用JS跳转
                }
            } else {
                //如果counts>1返回错误信息！
                $fail = array(
                    'info' => '此邮箱或用户名已被注册'
                );
                $this->ajaxReturn($fail);//返回前端，用JS跳转
            }
        }
    }

    public function email()
    {
        if ($this->check_verify($_POST['Code'])) {
            $this->error('验证码错误');
        }
        $email = $_POST['email'];
        //安全验证
        $id = $this->getRandOnlyId();
        //安全验证
        $id = $this->getRandOnlyId();
        //将随机数写入cookie进行安全验证！！
        cookie('id', $id, 3600);
        cookie('email', $email, 3600);
        if (SendMail($_POST['email'], "您好，请点击链接修改密码！", "http://localhost/cnsecer-ThinkAdmin-master/cnsecer-ThinkAdmin-master/shopshop/index.php?m=&c=Login&a=alter&tg_id=$id&email=$email")) {
            $this->success('发送成功，请注意查收您的邮箱！');
        }


    }


//验证码
    public function verify()
    {
        $Verify = new \Think\Verify();
        $Verify->codeSet = '0123456789';
        $Verify->fontSize = 13;
        $Verify->length = 4;
        $Verify->entry();
    }

    protected function check_verify($code)
    {
        $verify = new \Think\Verify();
        return $verify->check($code);
    }

    //Author:铜豌豆
    //QQ:309581329
    //Email:bestphper@126.com
    //http://gongwen.sinaapp.com
    //产生一个随机数进行安全验证
    function getRandOnlyId()
    {
        //新时间截定义,基于世界未日2012-12-21的时间戳。
        $endtime = 1356019200;//2012-12-21时间戳
        $curtime = time();//当前时间戳
        $newtime = $curtime - $endtime;//新时间戳
        $rand = rand(0, 99);//两位随机
        $all = $rand . $newtime;
        $onlyid = base_convert($all, 10, 8);//把10进制转为36进制的唯一ID
        return $onlyid;
    }
}



