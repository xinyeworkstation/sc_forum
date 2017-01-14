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
    //favorite表中我收藏的作品
    //favorite,work_id,user_id,我收藏的作品
    //work,cate_id,works,workname,download,favor
    //user,username,headimg
     public function collection(){
        $favorite=M('favorite');
        $where['f.user_id']=1;//找我的收藏，user_id=1是舒映的收藏
        $favor=$favorite->where($where)
                    ->alias('f')
                    ->field('w.cate_id,w.works,w.workname,u.username,u.headimg,w.download,w.favor')
                    ->join('work w on f.work_id=w.id')
                    ->join('user u on w.user_id=u.id')
                    ->select();
        $count=count($favor);           
        for($i=0;$i<$count;$i++){
            $favor[$i]['headimg']='/'.$favor[$i]['headimg'];//处理头像
            $img=get_url($favor[$i]['works']);//处理图片
            $favor[$i]['works']='/'.$img[0];
            $arr=$this->compare($favor[$i]['cate_id']);
         }
         var_dump($arr);
         $arr2=$this->ww($arr);
         $this->assign('arr',$arr);  
        $this->assign('work',$favor);
        $this->display();

    }
    private function ww($arr2){
    	var_dump($arr2);
    }
    private function compare($cate_id){
    	static $arr=array();
    		if(strcmp($cate_id, '1') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '2') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '3') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '4') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '5') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '6') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '7') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '8') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '9') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '10') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '11') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '12') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '13') == 0){
            	$arr[$cate_id]++;
            }
            if(strcmp($cate_id, '14') == 0){
            	$arr[$cate_id]++;
            }
            return $arr;
    }

    public function ChangePassword()
    {
        if (IS_POST) {
            if ($_POST['user_test'] == session('password_id')) {
                echo "123";
            } else {
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

    public function email_verify()
    {
        $id = $this->getRandOnlyId();
        session('password_id', $id);//记录id到session通过邮箱匹配修改密码
        $user['email'] = '975289275@qq.com';
        SendMail($user['email'], "您好，请点击链接修改密码！", "您的验证码是:" . $id . "/n" . "打死也不要给别人看到哦！");

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



       
        public function production()
        {
            var_dump($_GET);
            //flag 0禁用 1通过 2待审核 3不通过,要将相应的地方的作品放到相应的地方
            $work = M('work');
            $id = 1;
            $where['w.user_id'] = 1;
            $allow = 0;
            $undeter = 0;
            $ban = 0;
            //统计审核，未审核，不通过
            $flag = $work->where('user_id=' . $id)
                ->field('flag,cate_id')
                ->select();
            $flagc = count($flag);
            for ($i = 0; $i < $flagc; $i++) {
                if (strcmp($flag[$i]['flag'], '1') == 0) {
                    $allow++;
                } elseif (strcmp($flag[$i]['flag'], '2') == 0) {
                    $undeter++;
                } elseif (strcmp($flag[$i]['flag'], '3') == 3) {
                    $ban++;
                }
            $arr=$this->compare($flag[$i]['cate_id']);
            }

            //查出数据分配到前端
            $where['w.flag'] = '1';
            $work1 = $work->where($where)
                ->alias('w')
                ->field('w.flag,w.cate_id,w.works,w.workname,u.username,u.headimg,w.download,w.favor')
                ->join('user u on w.user_id=u.id')
                ->select();
            $where['w.flag'] = '2';
            $work2 = $work->where($where)
                ->alias('w')
                ->field('w.flag,w.cate_id,w.works,w.workname,u.username,u.headimg,w.download,w.favor')
                ->join('user u on w.user_id=u.id')
                ->select();
            $where['w.flag'] = '3';
            $work3 = $work->where($where)
                ->alias('w')
                ->field('w.flag,w.cate_id,w.works,w.workname,u.username,u.headimg,w.download,w.favor')
                ->join('user u on w.user_id=u.id')
                ->select();
            $count1 = count($work1);
            for ($x = 0; $x < $count1; $x++) {
                $wor1k[$x]['headimg'] = '/' . $work1[$x]['headimg'];//处理头像
                $img = get_url($work1[$x]['works']);//处理图片
                $work1[$x]['works'] = '/' . $img[0];
            }
            $count2 = count($work2);
            for ($y = 0; $y < $count2; $y++) {
                $work2[$y]['headimg'] = '/' . $work2[$y]['headimg'];//处理头像
                $img = get_url($work2[$y]['works']);//处理图片
                $work2[$y]['works'] = '/' . $img[0];
            }
            $count3 = count($work3);
            for ($z = 0; $z < $count3; $z++) {
                $work3[$z]['headimg'] = '/' . $work3[$z]['headimg'];//处理头像
                $img = get_url($work3[$z]['works']);//处理图片
                $work3[$z]['works'] = '/' . $img[0];
            }
            $this->assign('allow', $allow);
            $this->assign('undeter', $undeter);
            $this->assign('ban', $ban);
            $this->assign('arr', $arr);
            $this->assign('work1', $work1);
            $this->assign('work2', $work2);
            $this->assign('work3', $work3);
            $this->display();
        }
        public function vip(){
            $this->display();
        }
        public function recharge(){
            $this->display();
        }
        public function withdraw(){
            $this->display();
        }

        public function transationRecord()
        {
            $where['user_id'] = 1;//$_SESSION['user_id'];
            $model = M('business');
            $business = $model->field('b_money,time,money,flag')
                ->where($where)
                ->order('time desc')
                ->select();
            $user = M('user');
            $money = $user->where($where)->field('money')->find();
            $this->assign('business', $business);
            $this->assign('money', $money);
            $this->display();

        }

}
