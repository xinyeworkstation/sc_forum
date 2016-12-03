<?php

namespace Admin\Controller;

use Think\Controller;

class CommentController extends Controller{

    public function index($key="")
    {
        if($key == ""){
            $model = M('Comment');
        }else{
            $where['content'] = array('like',"%$key%");
            $model = M('Comment')->where($where);
        }
        $count  = count($model->select());// 查询满足要求的总记录数
        $Page = new \Extend\Page($count,15);// 实例化分页类传入总记录数和每页显示的记录数(15)
        $show = $Page->show();// 分页显示输出
        $model = $model->limit($Page->firstRow.','.$Page->listRows)
                        ->where($where)->order('id ASC')
                        ->alias('a')
                        ->field('a.id,b.username,a.content,a.time,a.flag')
                        ->join('user b on a.user_id=b.id')
                        ->select();
        $count=count($model);

        for($i = 0; $i < $count; $i++ ){
            //var_dump($model[$i]['content']);
            if(strlen($model[$i]['content']) > 60){
                $model[$i]['content']=substr($model[$i]['content'],0,60);
                $model[$i]['content'].="...";
            }
            //var_dump($model[$i]['content']);
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
        $arr[0]['flag']='0';
        if($model->save($arr[0])){
            $this->success('禁用成功',U('comment/index'));
        }else{
            $this->error("未做任何修改,禁用失败，还在显示中");
        }

    }

    public function show(){
        $id = $_GET['id'];
        $where['id'] = $id;
        $model = M('Comment');
        $arr=$model->where($where)->select();
        $arr[0]['flag']='1';
        if($model->save($arr[0])){
            $this->success('启用成功',U('comment/index'));
        }else{
            $this->error("未做任何修改,启用失败，还在禁用中");
        }

    }



}
