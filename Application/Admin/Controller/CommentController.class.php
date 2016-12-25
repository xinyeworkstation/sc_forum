<?php
namespace Admin\Controller;
use Think\Controller;

class CommentController extends BaseController{

    public function index($key="")
    {
        $status = I('get.status');//从sidebar传过来的，将相应的版区评论放在相应的版区
        if(!($key == "")){
            $status=I('post.status');//从相应的版区传过来的，模糊查询是，将相应的版区评论放在本版区
            $where['content'] = array('like',"%$key%");
            $where['catename'] = array('like',"%$key%");
            $where['_logic'] = 'or';
        }
        $where['c.status']=$status;
        $model = M('Comment');
        $count = $model->where($where)
                        ->alias('c')
                        ->join('category ca ON c.user_id=ca.id')
                        ->field('c.id,u.username,c.content,c.time,c.status,w.workname,ca.catename')
                        ->count();
        $Page = new \Extend\Page($count,15);// 实例化分页类传入总记录数和每页显示的记录数(15)
        $show = $Page->show();// 分页显示输出
        $model = $model->limit($Page->firstRow.','.$Page->listRows)
            ->where($where)
            ->order('c.id')
            ->alias('c')
            ->field('c.id,u.username,c.content,c.time,c.status,c.parent_id,w.workname,ca.catename')
            ->join('user u on c.user_id=u.id')
            ->join('work w on c.work_id=w.id')
            ->join('category ca on c.cate1_id=ca.id')
            ->select();
        for($i = 0; $i < $count; $i++ ){
            if(strlen($model[$i]['content']) > 60){
                $model[$i]['content']=substr($model[$i]['content'],0,60);
                $model[$i]['content'].="...";
            }
        }
        $this->assign('model',$model);
        $this->assign('page',$show);
        $this->display();
    }//index ok

     

    public function forbid(){
        //禁止这条评论时，找到这条评论以及下面的评论，全部让其status置为0
        $id = $_GET['id'];
        $where['id'] = $id;
        $model = M('Comment');
        $del=M('delete');
        $comment1=$model->where($where)->field('id,parent_id')->find();
        $arr[]=$comment1['id'];//将当前评论的id给arr数组
        $res=$this->getComment($comment1['id']);//得到当前评论的所有评论
        $arr=$this->getCommentId($res,$arr);//得到所有评论的id
       	var_dump($arr);
       	$wh['id'] = array('in',$arr);
       	$update['status']='0';
        $ban=$model->where($wh)->save($update);
        $count=count($arr);

        for($i=0;$i<$count;$i++){
            $whe['table']='comment';
            $whe['t_id']=$arr[$i];
            $whe['time']=time()+7*24*60*60; 
            $ban2=$del->add($whe);
        }
        if($ban){
            $this->success('禁用成功');
        }else{
            $this->error("禁用失败");
        }
    }//forbid ok

    public function show(){
        //通过一条评论时，其上的评论也会被通过，有没有
        $id = $_GET['id'];
        $where['id'] = $id;
        $model = M('Comment');
        $del=M('delete');
        $comment1=$model->where($where)->find();
        $res=$this->getComment2($comment1['parent_id']);//得到当前评论的所有评论
        $arr[]=$comment1['id'];//将当前评论的id给arr数组
        $arr=$this->getCommentId2($res,$arr);//得到所有评论的id
       	var_dump($arr);
       	$wh['id'] = array('in',$arr);
       	$update['status']='1';
        $pass=$model->where($wh)->save($update);
        $whe['t_id']=array('in',$arr);
        $whe['table']='comment';
        $allow=$del->where($whe)->delete();
        if($pass){
            $this->success('启用成功');
        }else{
            $this->error("启用失败");
        }

    }//show ok
    

    public function delete(){
        $id = $_GET['id'];
        $where['id'] = $id;
        $model = M('Comment');
        $comment1=$model->where($where)->field('id,parent_id,status')->find();

        $arr[]=$comment1['id'];//将当前评论的id给arr数组
        $res=$this->getComment($comment1['id']);
        $arr=$this->getCommentId($res,$arr);//得到所有评论的id
        var_dump($arr);

        $count=count($arr);
        $wh['id'] = array('in',$arr);
        $del=$model->where($wh)->delete();
        if($del){
            $this->success('删除成功');
        }else{
            $this->error("删除失败");
        }

    }// delete ok


    //得到所有的评论
    private function getComment($parent_id,&$result=array()){  
        $where['parent_id']=$parent_id;    
        $arr = M('comment')->where($where)->select();
        if(empty($arr)){
            return array();
        }
        foreach ($arr as $cm) {
            $thisArr=&$result[];
            $cm["children"] = $this->getComment($cm["id"],$thisArr);
            $thisArr = $cm; 
        }
        return $result;
    }

    //将得到的评论，得到所有的评论id，便于删除
    private function getCommentId($res,&$arr){
        foreach ( $res as $key ) {
            $thisArr=&$arr[];
            //var_dump($key);
           // echo '123';
            $thisArr=$key['id'];
            //var_dump($arr);
            if($key['children']){
                $this->getCommentId($key['children'],$arr);
            }
        }
        return $arr;
    }

    
      //得到所有的评论
    private function getComment2($id,&$result=array()){
        $where['id']=$id;    //3
        $arr = M('comment')->where($where)->select();
        if(empty($arr)){
            return array();
        }
        foreach ($arr as $cm) {
            $thisArr=&$result[];
            $cm["parent"] = $this->getComment2($cm["parent_id"],$thisArr);
            $thisArr = $cm; 
        }
        return $result;
    }
    private function getCommentId2($res,&$arr){
        foreach ( $res as $key ) {
            $thisArr=&$arr[];
            //var_dump($key);
           // echo '123';
            $thisArr=$key['id'];
            //var_dump($arr);
            if($key['parent']){
                $this->getCommentId($key['parent'],$arr);
            }
        }
        return $arr;
    }




    public function more(){
        $id = $_GET['id'];
        $where['id'] = $id;
        $model = M('Comment');
        $model = $model->where($where)->find();
        
        $this->assign('model',$model);
        $this->display();

    }

}
