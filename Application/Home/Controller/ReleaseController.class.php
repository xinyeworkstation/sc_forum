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
		if(empty($_FILES['picname']['name']) || empty($_FILES['compress']['name'])){
					$this->error('请选择要上传的作品!');
				}
		$files = array();
		$files = $_FILES;
		if(!empty($_FILES['flash']['name'])){
			$flash = $_FILES['flash'];
		}
		
		//将$_FILES数组的格式进行改变，变成单个作品的属性在同一数组下，在进行upload调用时，将文件单个进行上传
		$_FILES = array();
		//$n = count($files['picname']['name']);	
		foreach($files as $key => $name){
			//picname或compress上传的文件个数
			$n = count($files[$key]['name']);
			if($key == 'flash')
				continue;
			for($i=0;$i<$n;$i++){
				$_FILES[$key][$i]['name'] = $name['name'][$i];
				$_FILES[$key][$i]['type'] = $name['type'][$i];
				$_FILES[$key][$i]['tmp_name'] = $name['tmp_name'][$i];
				$_FILES[$key][$i]['error'] = $name['error'][$i];
				$_FILES[$key][$i]['size'] = $name['size'][$i];
				
			}
		}
		//var_dump($_FILES);var_dump($flash);
		//exit;
		$dir = I('post.cate_id');	//上传的文件子目录
		if(!$dir){
			$this->error('请选择作品所属版区');
		}
		//计算上传了几张图片
		$inum = count($_FILES['picname']);
		//计算上传几个压缩包
		$cnum = count($_FILES['compress']);
		//循环将图片单个上传
		for($i=0;$i<$inum;$i++){
			$config = array(
			'maxSize' => 3145728,
			'savePath' => 'Uploads/work/img/'.$dir.'/',
			'rootPath' => './Public/',
			'saveName' => md5(uniqid(microtime(true),true)),
			'exts' => array('jpg','jpeg','png','gif'),
			);
			$file = array();
			$file[] = $_FILES['picname'][$i];
			
			//$config初始化上传信息,$file单个文件进行上传
			$upload = upload($config,$file);
			//得到图片的保存路径，用@and进行分隔路径，保存在数据库
			$works .= '@and'.'./Public/'.$upload[0]['savepath'].$upload[0]['savename'];
			$works = ltrim($works,'@and');
		}
		
		//循环将压缩包单个上传
		for($i=0;$i<$cnum;$i++){
			$config = array(
			'maxSize' => 10*1024*1024,
			'savePath' => 'Uploads/work/compress/'.$dir.'/',
			'rootPath' => './Public/',
			'saveName' => md5(uniqid(microtime(true),true)),
			'exts' => array('rar','zip'),
			);
			
			$file = array();
			$file[] = $_FILES['compress'][$i];
			$upload = upload($config,$file);
			//得到压缩包保存的路径，多个文件之间的路径用@and分隔保存在数据库
			$compress .= '@and'.'./Public/'.$upload[0]['savepath'].$upload[0]['savename'];
			$compress = ltrim($compress,'@and');

			$size .= '@and'.$upload[0]['size'];
			$size = ltrim($size,'@and');
			//var_dump($size);exit;
		}
	
		if(!empty($flash)){
			$config = array(
			'maxSize' => 10*1024*1024,
			'savePath' => 'Uploads/work/flash/'.$dir.'/',
			'rootPath' => './Public/',
			'saveName' => md5(uniqid(microtime(true),true)),
			'exts' => array('swf'),
			);
			$file[] = $flash;
			$upload = upload($config,$file);
			$flash = './Public/'.$upload[1]['savepath'].$upload[1]['savename'];
		}
		
		if(!$upload==true){
			$this->error($upload);			
		}
		$data['workname'] = trim(I('post.workname'));
		$data['cate_id'] = I('post.cate_id');
		$data['intro'] = trim(I('post.intro'));
		foreach($data as $v){
			if(strlen(trim($v))==0){
				$this->error('输入的值不能为空');
			}
		}
		//将输入的金币整型化
		$data['price'] = (int)floor(I('post.price'));
		$data['works'] = $works;
		$data['compress'] = $compress;
		$data['flash'] = $flash;
		$data['size'] = $size;
		if(!is_numeric($data['price'])){
			$this->error('您输入的金币有误，请输入一个整数!!!');
		}
		$data['user_id'] = 1;	
		$model = M('work');
		$num = $model->add($data);
		if($num){
			$this->success('添加作品成功!');
		}else{
			//如果添加失败则删除上传的文件
			$work = get_url($works);
			$compress = get_url($compress);
			$url = array_merge($work,$compress);
			$n = count($url);
			for($i=0;$i<$n;$i++){
				unlink($url[$i]);
			}
			$this->error('添加作品失败！');
		}

	}
}