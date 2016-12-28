<?php
namespace Home\Controller;
use Think\Controller;
class PersonController extends Controller {

    public function index(){
        $this->display();
    }
     public function changePassword(){
        $this->display();
    }
    
     public function personMessage(){
        $this->display();
    }



     public function collection(){
        $work=M('work');
        $where['w.user_id']=1;
        $work=$work->where($where)
                    ->alias('w')
                    ->field('w.cate_id,w.works,w.workname,u.username,u.headimg,w.download,w.favor')
                    ->join('user u on w.user_id=u.id')
                    ->select();
        $count=count($work);           
        for($i=0;$i<$count;$i++){
            $work[$i]['headimg']='/'.$work[$i]['headimg'];//处理头像
            $img=get_url($work[$i]['works']);//处理图片
            $work[$i]['works']='/'.$img[0];
        }
        $this->assign('work',$work);
        $this->display();
    }
     public function production(){
        $flag=I('get.flag');
        //flag 0禁用 1通过 2待审核 3不通过
        $work=M('work');
        $where['w.user_id']=1;
        $work=$work->where($where)
                    ->alias('w')
                    ->field('w.flag,w.cate_id,w.works,w.workname,u.username,u.headimg,w.download,w.favor')
                    ->join('user u on w.user_id=u.id')
                    ->select();
        $count=count($work);
        $allow=0;
        $undeter=0;
        $ban=0;
        for($i=0;$i<$count;$i++){
            $work[$i]['headimg']='/'.$work[$i]['headimg'];//处理头像
            $img=get_url($work[$i]['works']);//处理图片
            $work[$i]['works']='/'.$img[0];
            if(strcmp($work[$i]['flag'],'1')==0){
                $allow++;
            }elseif(strcmp($work[$i]['flag'],'2')==0){
                $undeter++;
            }else{
                $ban++;
            }
        }
        $this->assign('allow',$allow);
        $this->assign('undeter',$undeter);
        $this->assign('ban',$ban);
        $this->assign('img',$img);
        $this->assign('work',$work);
        $this->display();
    }
     public function transationRecord(){
        $where['user_id'] = 1;//$_SESSION['user_id'];
        $model = M('business');
        $business = $model->field('b_money,time,money,flag')
                          ->where($where)
                          ->order('time desc')
                          ->select();
        $user = M('user');
        $money = $user->where($where)->field('money')->find();
        $this->assign('business',$business);
        $this->assign('money',$money);
        $this->display();
    }
}
