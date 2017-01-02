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
        }
        $this->assign('work',$favor);
        $this->display();
    }


     public function production(){
     	var_dump($_GET);
	    //flag 0禁用 1通过 2待审核 3不通过,要将相应的地方的作品放到相应的地方
	    $work=M('work');
	    $id=1;
	    $where['w.user_id']=1;
	    $allow=0;
		$undeter=0;
		$ban=0;
		//统计审核，未审核，不通过
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

	    //查出数据分配到前端
	    $where['w.flag']='1';
	    $work1=$work->where($where)
	                ->alias('w')
	                ->field('w.flag,w.cate_id,w.works,w.workname,u.username,u.headimg,w.download,w.favor')
	                ->join('user u on w.user_id=u.id')
	                ->select();  
	    $where['w.flag'] = '2';
	    $work2=$work->where($where)
	                ->alias('w')
	                ->field('w.flag,w.cate_id,w.works,w.workname,u.username,u.headimg,w.download,w.favor')
	                ->join('user u on w.user_id=u.id')
	                ->select();
	    $where['w.flag'] = '3';
	    $work3=$work->where($where)
	                ->alias('w')
	                ->field('w.flag,w.cate_id,w.works,w.workname,u.username,u.headimg,w.download,w.favor')
	                ->join('user u on w.user_id=u.id')
	                ->select();


	    $count1=count($work1);
	    for($x=0;$x<$count1;$x++){
	        $work1[$x]['headimg']='/'.$work1[$x]['headimg'];//处理头像
	        $img=get_url($work1[$x]['works']);//处理图片
	        $work1[$x]['works']='/'.$img[0];
	    }

	    $count2=count($work2);
	    for($y=0;$y<$count2;$y++){
	        $work2[$y]['headimg']='/'.$work2[$y]['headimg'];//处理头像
	        $img=get_url($work2[$y]['works']);//处理图片
	        $work2[$y]['works']='/'.$img[0];
	    }
	    $count3=count($work3);
	    for($z=0;$z<$count3;$z++){
	        $work3[$z]['headimg']='/'.$work3[$z]['headimg'];//处理头像
	        $img=get_url($work3[$z]['works']);//处理图片
	        $work3[$z]['works']='/'.$img[0];
	    }
	    $this->assign('allow',$allow);
	    $this->assign('undeter',$undeter);
	    $this->assign('ban',$ban);
	    $this->assign('work1',$work1);
	    $this->assign('work2',$work2);
	    $this->assign('work3',$work3);
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
