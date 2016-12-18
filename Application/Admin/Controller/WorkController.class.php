<?php
	namespace Admin\Controller;
	use Think\Controller;

	class WorkController extends BaseController{
		function index($key=""){
			if(!$key==""){
				$where['workname'] = array("like","%$key%");
				$where['username'] = array("like","%$key%");
				$where['_logic'] = "or";
				
			}
			$model = M('work');
			$where['flag'] = $_GET['flag'];
			$count = $model->alias('w')
						   ->join('user u ON w.user_id=u.id')
						   ->field('w.id,workname,w.price,u.username,w.flag')
						   ->where($where)
						   ->count();
			$Page = new \Extend\Page($count,15);
			$show = $Page->show();
			$work = $model->alias('w')
						  ->join('user u ON w.user_id=u.id')
						  ->limit($Page->firstRow.','.$Page->listRow)
						  ->field('w.id,workname,w.price,u.username,w.flag')
						  ->where($where)
						  ->order('w.id DESC')
						  ->select();
			$this->assign('work',$work);
			$this->assign('show',$show);
			$this->display();
		}

		function add(){
			if(!IS_POST){
				$model = M('category');
				$cate = $model->select();	//查出所有的版区分类
				$this->assign('cate',$cate);
				$this->display();
			}
			if(IS_POST){
				//var_dump($_FILES);
				if(empty($_FILES['picname']) || empty($_FILES['compress'])){
					$this->error('请选择要上传的作品!');
				}
				$files = array();
				$files = $_FILES;
				//将$_FILES数组的格式进行改变，变成单个作品的属性在同一数组下，在进行upload调用时，将文件单个进行上传
				$_FILES = array();
				//$n = count($files['picname']['name']);	
				foreach($files as $key => $name){
					//picname或compress上传的文件个数
					$n = count($files[$key]['name']);
					for($i=0;$i<$n;$i++){

						$_FILES[$key][$i]['name'] = $name['name'][$i];
						$_FILES[$key][$i]['type'] = $name['type'][$i];
						$_FILES[$key][$i]['tmp_name'] = $name['tmp_name'][$i];
						$_FILES[$key][$i]['error'] = $name['error'][$i];
						$_FILES[$key][$i]['size'] = $name['size'][$i];
						
					}
				}
				//var_dump($_FILES);
				//exit;
				$dir = I('post.pid');	//上传的文件子目录
				if(!$n){
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
				}

				//exit;
				if(!$upload==true){
					$this->error($upload);			
				}
				$data['workname'] = trim(I('post.workname'));
				$data['cate_id'] = I('post.pid');
				$data['intro'] = I('post.intro');
				foreach($data as $v){
					if(strlen(trim($v))==0){
						$this->error('输入的值不能为空');
					}
				}
				//将输入的金币整型化
				$data['price'] = (int)floor(I('post.price'));
				$data['works'] = $works;
				$data['compress'] = $compress;
				if(!is_numeric($data['price'])){
					$this->error('您输入的金币有误，请输入一个整数!!!');
				}
				$data['user_id'] = 1;	//默认作者为管理员
				$model = M('work');
				$num = $model->add($data);
				if($num){
					$this->success('添加作品成功!',U('work/index?flag=1'));
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

		function verify($id){
			$model = M('work');
			$model2 = M('category');
			if(!IS_POST){
				
				$cate = $model2->select();
				$work = $model->alias('w')
							  ->join('user u on w.user_id=u.id')
							  ->field('w.id,workname,works,cate_id,price,intro,u.username')
							  ->where('w.id='.$id)
							  ->find();
				$url = get_url($work['works']);//得到图片的路径
				$this->assign('url',$url);
				$this->assign('cate',$cate);
				$this->assign('work',$work);
				$this->display();
			}
			if(IS_POST){
				$id = $_POST['id'];
				$data['workname'] = trim(I('post.workname'));
				$data['cate_id'] = I('post.pid');
				$data['intro'] = I('post.intro');
				$data['flag'] = I('post.flag');
				foreach($data as $v){
					if(strlen(trim($v))==0){
						$this->error('输入的值不能为空');
					}
				}
				$data['price'] = (int)floor(I('post.price'));
				if(!is_numeric($data['price'])){
					$this->error('您输入的金币有误，请输入一个整数!!!');
				}
				$num = $model->where('id='.$id)->save($data);
				if($num){
					$this->success('操作成功!',U('work/index?flag=2'));
				}else{
					$this->error('操作失败!');
				}
			}
		}

		function edit($id){
			if(!IS_POST){
				$model = M('work');
				$model2 = M('category');
				$cate = $model2->select();
				$work = $model->alias('w')
							  ->join('user u on w.user_id=u.id')
							  ->field('w.id,workname,works,cate_id,price,intro,u.username')
							  ->where('w.id='.$id)
							  ->find();
				$url = get_url($work['works']);
				$this->assign('url',$url);
				$this->assign('cate',$cate);
				$this->assign('work',$work);
			}
			if(IS_POST){
				$id = $_POST['id'];
			}
			$this->display();
		}

		function delete($id){
			$model = M('work');
			$where['id'] = $id;
			$work = $model->where($where)->find();
			$work['flag'] = '0';
			$num = $model->where($where)->save($work);
			if($num){
				$this->success('删除成功!');
			}else{
				$this->error('删除失败!');
			}
		}

		/*public function water(){
			$id=I('get.id');
			$model = M('work');
			$where['id']=$id;
			$work = $model->where($where)
							->field('works')
							->find();
			$url = array();
			$url = get_url($work['works']);
			$count=count($url);
			$image = new \Think\Image();
			for($i=0;$i<$count;$i++){
				$image->open($url[$i])->water('./Public/Images/logo.jpg',\Think\Image::IMAGE_WATER_NORTHEAST)->save($url[$i]);
			}
			//redirect();
			redirect(U('work/verify') ,2 ,'正在跳转，请稍候......' );
		} */
	}