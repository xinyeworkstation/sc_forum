<?php
namespace Admin\Controller;
use Think\Controller;

class CommentController extends BaseController{

    public function index($key="")
    {
        $cate_id = I('get.cate_id');//从sidebar传过来的，将相应的版区评论放在相应的版区
        if(!($key == "")){
            $cate_id=I('post.cate_id');//从相应的版区传过来的，模糊查询是，将相应的版区评论放在本版区
            $where['content'] = array('like',"%$key%");
        }
        $where['cate1_id']=$cate_id;
        $model = M('Comment');
        $count = $model->where($where)->count();
        $Page = new \Extend\Page($count,15);// 实例化分页类传入总记录数和每页显示的记录数(15)
        $show = $Page->show();// 分页显示输出
        $model = $model->limit($Page->firstRow.','.$Page->listRows)
            ->where($where)
            ->order('status DESC')
            ->alias('a')
            ->field('a.id,b.username,a.content,a.time,a.status,c.workname,a.cate1_id')
            ->join('user b on a.user_id=b.id')
            ->join('work c on a.work_id=c.id')
            ->select();
        for($i = 0; $i < $count; $i++ ){
            //var_dump($model[$i]['content']);
            if(strlen($model[$i]['content']) > 60){
                $model[$i]['content']=substr($model[$i]['content'],0,60);
                $model[$i]['content'].="...";
            }
        }
        $this->assign('model',$model);
        $this->assign('page',$show);
        $this->display();
    }


    public function forbid(){
        $id = $_GET['id'];
        $where['id'] = $id;
        $model = M('Comment');
        $arr=$model->where($where)->select();
        $arr[0]['status']='0';
        if($model->save($arr[0])){
            $this->success('禁用成功');
        }else{
            $this->error("未做任何修改,禁用失败，还在显示中");
        }

    }

    public function show(){
        $id = $_GET['id'];
        $where['id'] = $id;
        $model = M('Comment');
        $arr=$model->where($where)->select();
        $arr[0]['status']='1';
        if($model->save($arr[0])){
            $this->success('启用成功');
        }else{
            $this->error("未做任何修改,启用失败，还在禁用中");
        }

    }
    public function delete(){
        $id = $_GET['id'];
        $where['id'] = $id;
        $model = M('Comment');
        $result = $model->where($where)->delete();
        if($result){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }

    }
    public function more(){
        $id = $_GET['id'];
        $where['id'] = $id;
        $model = M('Comment');
        $model = $model->where($where)->find();
        $this->assign('model',$model['content']);
        $this->display();

    }

}
