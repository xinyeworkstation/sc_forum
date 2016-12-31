<?php
	namespace Admin\Controller;
	use Think\Controller;

	class BusinessController extends BaseController{
		function index($key=""){
			$model = M('business');
			if(!$key==""){
				//$where['workname'] = array("like","%$key%");
				$where['username'] = array("like","%$key%");
				$where['_logic'] = "or";
			}
			$count = $model->alias('c')
						   ->join('user u ON c.user_id=u.id ')
						   ->field('c.id,c.b_money,c.money,time,flag,u.username')
						   ->where($where)
						   ->count();
			$Page = new \Extend\Page($count,15); 
			$show = $Page->show();
			$consume = $model->alias('c')
						   ->join('user u ON c.user_id=u.id ')
						   ->limit($Page->firstRow.','.$Page->listRow)
						   ->field('c.id,c.b_money,c.money,time,flag,u.username')
						   ->where($where)
						   ->order('time DESC')
						   ->select();
			$this->assign('consume',$consume);
			$this->assign('show',$show);
			$this->display();
		}

		/*function delete($id){
			$model = M('business');
			$data['status'] = '0';
			$row = $model->where('id='.$id)->save($data);
			if($row){
				$num = fobidden('business',$id,'status');
				if($num){
					$this->success('删除交易记录成功!');
				}else{
					$this->error('删除交易记录失败!');
				}
			}		
		}*/
	}