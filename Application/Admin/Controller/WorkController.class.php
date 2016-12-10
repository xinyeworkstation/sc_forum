<?php
	namespace Admin\Controller;
	use Think\Controller;

	class WorkController extends Controller{
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
			}
			if(IS_POST){
				
				/*$config = array(
					'maxSize' => 3145728,
					'savePath' => 'Uploads/img/',
					'rootPath' => './Public/',
					'saveName' => time().'_'.mt_rand(),
					'exts' => array('jpg','jpeg','png','gif','rar','zip'),
				);
				$upload = upload($config);
				//if($upload==true){
					echo 1;var_dump($upload);
					echo $upload[1]['savename'];exit;
					$this->success('上传成功');			
				//}else{

					$this->error($upload);
				//}*/
				$data['workname'] = trim(I('post.workname'));
				$data['cate_id'] = I('post.pid');
				$data['intro'] = I('post.intro');
				foreach($data as $v){
					if(strlen(trim($v))==0){
						$this->error('输入的值不能为空');
					}
				}
				$data['price'] = (int)floor(I('post.price'));
				if(!is_numeric($data['price'])){
					$this->error('您输入的金币有误，请输入一个整数!!!');
				}
				//$this->assign('data',$data);
				$model = M('work');
				$num = $model->add($data);
				if($num){
					$this->success('添加作品成功!');
				}else{
					$this->error('添加作品失败！');
				}
			}
			$this->display();
		}

		function verify($id){
			if(!IS_POST){
				$model = M('work');
				$model2 = M('category');
				$cate = $model2->select();
				$work = $model->alias('w')
							  ->join('user u on w.user_id=u.id')
							  ->field('workname,works,cate_id,price,compress,intro,u.username')
							  ->where('w.id='.$id)
							  ->find();
				//$work['cate_id'] = (int)$work['cate_id'];
				$compress = uncompress($work['compress']);
				$this->assign('compress',$compress);
				$this->assign('cate',$cate);
				$this->assign('work',$work);

			}
			if(IS_POST){
				$data['workname'] = trim(I('post.workname'));
				$data['cate_id'] = I('post.pid');
				$data['intro'] = I('post.intro');
				foreach($data as $v){
					if(strlen(trim($v))==0){
						$this->error('输入的值不能为空');
					}
				}
				$data['price'] = (int)floor(I('post.price'));
				if(!is_numeric($data['price'])){
					$this->error('您输入的金币有误，请输入一个整数!!!');
				}
			}
			$this->display();
		}

		function edit($id){

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
	}