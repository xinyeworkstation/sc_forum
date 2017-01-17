<?php
namespace Home\Controller;
use Think\Controller;

class ReleaseController extends Controller{
	public function index($key=""){
		if($key != ""){

			$this->display('index/index');
		}else{
			$cate = M('category')->select();
			$this->assign('cate',$cate);
			$this->display();
		}
		
	}

	function release(){
		$model = M('work');
		$data['user_id'] = $_SESSION['user_id'];
		$data['cate_id'] = I('post.cate_id');
		$data['price'] = (int)floor(I('post.price'));

	}
}