<?php
	namespace Admin\Controller;
	use Think\Controller;

	class WorkController extends Controller{
		function index($key=""){
			if(!$key==""){
				$where['workname'] = array("like","%$key%");
				$where['username'] = array("like","%$key%");
				$where['_logic'] = "or";
				$model = M('work');
			}
			$count = $model->alias('m')
						   ->join('user u ON m.user_id=u.id')
						   ->field('m.id,workname,m.price,u.username,m.flag')
						   ->where($where)
						   ->count();
			$Page = new Extend\Page($count,15);
			$show = $Page->show();
			$work = $model->alias('m')
						  ->join('user u ON m.user_id=u.id')
						  ->limit($Page->firstRow.','.$Page->listRow)
						  ->field('m.id,workname,m.price,u.username,m.flag')
						  ->where($where)
						  ->order('id DESC')
						  ->count();
			$this->assign('work',$work);
			$this->assign('show',$show);
			$this->display();
		}

		function add(){
			if(!IS_POST){
				$model = M('category');
				$cate = $model->select();	//查出所有的版区分类
				$this->assign('cate',$cate);
			}
			if(IS_POST){
				
			}
			$this->display();
		}

		function verify($id){
			$this->display();
		}
	}