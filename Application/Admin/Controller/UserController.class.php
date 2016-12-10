<?php

namespace Admin\Controller;
use Think\Controller;
/**
 * 用户管理
 */
class UserController extends Controller
{
	/**
	 * 显示用户
	 * @param  string  $key   查询关键字
	 * @param  int $level 用户等级
	 */
	public function index ($key='',$level=1) {
		if ($key != '') {
			if ($key == '正常') {
				$where['status'] = 1;
			} elseif ($key == '禁用'){
				$where['status'] = 0;
			} else {
				$where['username'] = array("like","%$key%");
			}
		}
		$model = M('user');
		$where['level'] = $level;
		$count = $model->where($where)->count();
		$Page = new \Extend\Page($count,25);
		$page = $Page->show();
		$user = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id desc')->select();
		$this->assign('user',$user);
		$this->assign('level',$level);
		$this->assign('page',$page);
		$this->display();
	}

	/**
	 * 修改用户信息
	 * @param  [int] $id 用户id
	 */
	public function update ($id,$level) {
		if (!IS_POST) {
			$model = M('user');
			$where['id'] = $id;
			$user = $model->where($where)->find();
			$this->assign('user',$user);
			$this->display();
		} else {
			$model = M('user');
			$where['id'] = I('id');
			$data['username'] = I('username');
			$password = I('password');
			$data['email'] = I('email');
			$data['money'] = I('money');
			$data['level'] = I('level');
			//除密码框外其余不能为空
			foreach ($data as $v) {
				if ($v == '') {
					$this->error('除密码框与头像外其余不能为空！！！');
				}
			}
			//检验邮箱格式
			$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
			if (!preg_match($pattern,$data['email'])) {
				$this->error('邮箱格式填写错误！！！');
			}
			//检验账户余额是否为数字
			if (!is_numeric($data['money'])) {
				$this->error('账户余额必须为数字！！！');
			}
			//修改头像
			if ($_FILES['picture']) {
				$upload = new \Think\Upload();// 实例化上传类 
				$upload->maxSize = 2097152 ;// 设置附件上传大小限制为2M 
				$upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型 
				$upload->savePath = 'Uploads/Face/'; // 设置附件上传目录    // 上传文件 
				$upload->rootPath = './Public/'; 
				$upload->saveName = time().'_'.mt_rand(); //图片保存名
				$info = $upload->upload();
				$url = './Public/'.$info['picture']['savepath'];
				$face_name = $url.$info['picture']['savename'];
				if (!$info) {
					$this->error($upload->getError());
				} else {//成功则对图片进行处理
					$image = new \Think\Image(); 
					$image->open($face_name);// 生成一个固定大小为100*130的缩略图并保存为thumb.jpg
					$data['headimg'] = $url.time().'_'.mt_rand().'.jpg';
					$image->thumb(100, 130,\Think\Image::IMAGE_THUMB_FIXED)->save($data['headimg']);
					unlink($face_name);//删除原图
				}
			}
			//表单为空则不修改密码
			if (empty($password)) {
				$pw = $model->where($where)->field('password')->find();
				$data['password'] = $pw['password'];
			} else {
				$data['password'] = md5($password);
			}
			//该用户是否已被注册
			$arr = $model->where("username='".$data['username']."'")->field('id')->find();
			if ($arr['id'] != $where['id']) {
				$this->error('该用户名已被注册！！！');
			}
			$num = $model->where($where)->save($data);
			if ($num) {
				$this->success('修改用户成功！！！！',U('index',array('level'=>$level)));
			} else {
				$this->error('修改用户失败！！！！');
			}
		}
	}

	/**
	 * 添加用户
	 * @param int $level 默认为相应用户等级
	 */
	public function add ($level){
		if (!IS_POST) {
			$this->assign('level',$level);
			$this->display();
		} else {
			$user = D('User');
			if(!$user->create()){
				$this->error($user->getError());
			}
			$lastid = $user->add();
			if ($lastid) {
				$this->success('创建用户成功！！！！',U('index',array('level'=>$level)));
			} else {
				$this->error('创建用户失败！！！！');
			}
		}
	}

	/**
	 * 删除用户
	 * @param  [int] $id 用户id
	 */
	public function delete ($id){
		$model = M('user');
		$where['id'] = $id;
		$num = $model->where($where)->delete();
		if ($num) {
			$this->success('删除用户成功！！！');
		} else {
			$this->error('删除用户失败！！！');
		}
	}

	/**
	 * 禁止用户
	 * @param  [int] $id 用户id
	 */
	public function disable ($id,$act) {
		$model = M('user');
    	$data['id'] = $id;
    	if ($act == 'start') {
    		$data['status'] = 1;
    	} else {
    		$data['status'] = 0;
    	}
    	$num = $model->save($data);
    	if ($num) {
    		$this->success('操作成功');
    	} else {
    		$this->error('操作失败');
    	}
	}
	
}