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
	     	//var_dump($_GET);
	        $getflag=I('get.flag');
	        //flag 0禁用 1通过 2待审核 3不通过,要将相应的地方的作品放到相应的地方
	        $work=M('work');
	        $id=1;
	        $where['w.user_id']=1;
	       	$where['w.flag']=$getflag;
	        $allow=0;
	        $undeter=0;
	        $ban=0;
	        $flag=$work->where('user_id='.$id)
	                    ->field('flag')
	                    ->select();
	        $flagc=count($flag);
	        for($i=0;$i<$flagc;$i++){
	            if(strcmp($flag[$i]['flag'],'1')==0){
	                $allow++;
	            }elseif(strcmp($flag[$i]['flag'],'2')==0){
	                $undeter++;
	            }elseif(strcmp($flag[$i]['flag'],'3')==3){
	                $ban++;
	            }
	        }

	        $work=$work->where($where)
	                    ->alias('w')
	                    ->field('w.flag,w.cate_id,w.works,w.workname,u.username,u.headimg,w.download,w.favor')
	                    ->join('user u on w.user_id=u.id')
	                    ->select();
	        $count=count($work);
	        for($i=0;$i<$count;$i++){
	            $work[$i]['headimg']='/'.$work[$i]['headimg'];//处理头像
	            $img=get_url($work[$i]['works']);//处理图片
	            $work[$i]['works']='/'.$img[0];
	        }
	        $this->assign('allow',$allow);
	        $this->assign('undeter',$undeter);
	        $this->assign('ban',$ban);
	        $this->assign('work',$work);
	        $this->display();
    }
     public function transationRecord(){
        $user=M('user');
        $where['id']=1;
        $money=$user->where($where)->field('money')->find();
        $this->assign('money',$money);
        $this->display();
    }
}
