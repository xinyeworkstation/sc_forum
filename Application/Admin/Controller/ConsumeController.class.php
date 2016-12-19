<?php
	namespace Admin\Controller;
	use Think\Controller;

	class ConsumeController extends BaseController{
		function index($key=""){
			$model = M('Consume');
			if(!$key==""){
				$where['workname'] = array("like","%$key%");
				$where['username'] = array("like","%$key%");
				$where['_logic'] = "or";
			}
			$count = $model->alias('c')
						   ->join('user u ON c.user_id=u.id ')
						   ->field('c.id,c.money,time,workname,u.username')
						   ->where($where)
						   ->count();
			$Page = new \Extend\Page($count,15); 
			$show = $Page->show();
			$consume = $model->alias('c')
						   ->join('user u ON c.user_id=u.id ')
						   ->limit($Page->firstRow.','.$Page->listRow)
						   ->field('c.id,c.money,time,workname,u.username')
						   ->where($where)
						   ->order('id DESC')
						   ->select();
			$this->assign('consume',$consume);
			$this->assign('show',$show);
			$this->display();
		}

		function delete($id){
			$model = M('Consume');
			$where['id'] = $id;
			$num = $model->where($where)->delete();
			if($num){
				$this->success('删除消费记录成功!');
			}else{
				$this->error('删除消费记录失败!');
			}
		}
	}