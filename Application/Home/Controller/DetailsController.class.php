<?php
namespace Home\Controller;
use Think\Controller;

class DetailsController extends Controller {

	public function index ($wid=6) {
		//$uid = $_SESSION['user_id'];
		$uid = 3;
		//显示该作品相关信息
		$model = M('work');
		$display = $model->alias('w')
			  ->join('category c on cate_id=c.id')
			  ->where('w.id='.$wid)
			  ->field('w.*,catename')
			  ->find();

		//显示该作品的作者
		$user = M('user');
		$author = $user->where('id='.$display['user_id'])->field('username,headimg')->find();
		$user_money = $user->where('id='.$uid)->field('money')->find();

		$img = get_url($display['works']);
		$num = count($img);
		if ($num>4) {//前端只显示4个图片
			$firstimg = array_slice($img,0,4);
		}

		$str = 'JPG';
		if (!is_null($display['flash']) ) {
			$str.=',FLASH';
		}
		$display['wtime'] = date('Y-m-d H:i:s',$display['wtime']);
		$display['type'] = $str;//显示作品类型

		$url = get_url($display['compress']); //显示压缩包
		$size = get_url($display['size']);
		$name = basename($compress);
		$i=0;
		foreach ($url as $v) {
			$compress[$i]['url'] = $v;
			$compress[$i]['name'] = substr(basename($v),-10,10);
			$num = strrpos($compress[$i]['name'],'.');
			$compress[$i]['ext'] = substr($compress[$i]['name'],$num);
			$compress[$i]['size'] = statuSize($size[$i]);
			$i++;
		}
		//显示flash
		//if (!is_null($display['flash'])) {
			$flash = $display['flash'];
		//}
		//显示该用户是否已购买该作品
		$model = M('business');
		$where = array ('work_id'=>$wid,'user_id'=>$uid);
		$downFlag = $model->where($where)->count();


		//显示该用户是否收藏
		$model = M('favorite');
		$where = array('work_id'=>$wid,'user_id'=>$uid);
		$favor = $model->where($where)->count();
	

		$this->assign('author',$author);
		$this->assign('money',$user_money['money']);
		$this->assign('img',$firstimg);
		$this->assign('decimg',$img);
		$this->assign('display',$display);
		$this->assign('compress',$compress);
		$this->assign('name',$name);
		$this->assign('downFlag',$downFlag);
		$this->assign('favor',$favor);
		$this->assign('flash',$flash);
		$this->display();
	} 

	//购买作品
	public function buy () {
		$wid = I('wid');
		//$uid = $_SESSION['user_id'];
		$uid = 3;
		$umodel = M('user');
		$umoney = $umodel->where('id='.$uid)->field('money')->find();
		$wmodel = M('work');
		$wmoney = $wmodel->where('id='.$wid)->field('price')->find();

		//查询该用户是否已购买该作品
		$model = M('business');
		$where = array ('work_id'=>$wid,'user_id'=>$uid);
		$downFlag = $model->where($where)->count();
		if(!$downFlag){
			if ($wmoney['price'] <= $umoney['money']) {//可以购买
				$model = M('business');
				$data['b_money'] = $wmoney;
				$data['time'] = time();
				$data['flag'] = 0;
				$data['work_id'] = $wid;
				$data['user_id'] = $uid;
				$idnum = $model->add($data);
				if ($idnum) {
					$udata['money'] = $umoney['money'] - $wmoney['price'];
					$umodel->where('id='.$uid)->save($udata);
					$this->success('支付成功！');
				} else {
					$this->error('操作有误，支付失败！');
				}
			} else {
				$this->error('金币不够，支付失败！');
			}
		} else {
			$this->error('您已购买过该作品，请勿重复购买！');
		}
	}

	//更新收藏数
	public function putFavor () {
		$id = $_GET['id'];//作品id
		$flag = $_GET['al'];//设置收藏状态标志(1:加入收藏 2：取消收藏)
		if (IS_AJAX) {
			if($flag == 1){
				$model = M('favorite');
				$data['work_id'] = $id;
				//$data['user_id'] = $_SESSION['user_id'];
				$data['user_id'] = 3;
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
				//$where['user_id'] = $_SESSION['user_id'];
				$where['user_id'] = 3;
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