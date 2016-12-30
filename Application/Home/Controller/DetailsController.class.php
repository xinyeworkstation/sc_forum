<?php
namespace Home\Controller;
use Think\Controller;

class DetailsController extends Controller {

	public function index ($uid=1,$wid=6) {
		//显示该作品的作者
		$user = M('user');
		$author = $user->where('id='.$uid)->field('username,headimg')->find();
		//显示该作品相关信息
		$model = M('work');
		$display = $model->alias('w')
			  ->join('category c on cate_id=c.id')
			  ->where('w.id='.$wid)
			  ->field('w.*,catename')
			  ->find();
		$str = 'JPG';
		$img = get_url($display['works']);
		if (!is_null($display['flash']) ) {
			$str.=',FLASH';
		}
		$display['wtime'] = date('Y-m-d H:i:s',$display['wtime']);
		$display['type'] = $str;
		//显示该用户是否收藏
		$model = M('favorite');
		$where = array('work_id'=>$wid,'user_id'=>$uid);
		$favor = $model->where($where)->count();

		$this->assign('author',$author);
		$this->assign('img',$img);
		$this->assign('display',$display);
		$this->assign('favor',$favor);
		$this->display();
	} 

	//更新收藏数
	public function putFavor(){
		$id = $_GET['id'];//作品id
		$flag = $_GET['al'];//设置收藏状态标志(1:加入收藏 2：取消收藏)
		if (IS_AJAX) {
			if($flag == 1){
				$model = M('favorite');
				$data['work_id'] = $id;
				//$data['user_id'] = $_SESSION['uid'];
				$data['user_id'] = 1;
				$uid = $model->add($data);
				if ($uid) {
					$model = M('work');
					$favor = $model->where('id='.$id)->field('favor')->find();
					$favor['favor']++;//自增
					$a = $model->where('id='.$id)->save($favor);
					echo $a;
				} else {
					echo 0;
				}
					
			} else {
				$model = M('favorite');
				$where['work_id'] = $id;
				//$where['user_id'] = $_SESSION['uid'];
				$where['user_id'] = 1;
				$num = $model->where($where)->delete();
				if ($num) {
					$model = M('work');
					$favor = $model->where('id='.$id)->field('favor')->find();
					$favor['favor']--;//自减
					$a = $model->where('id='.$id)->save($favor);
					echo $a;
				} else {
					echo 0;
				}
				
			}
		} else {
			echo 0;
		}
		
	}

	
}