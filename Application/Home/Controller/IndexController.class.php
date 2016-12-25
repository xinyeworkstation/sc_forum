<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
    	if(!IS_POST){
    		$model = M('work');
    		$order = $model->alias('w')
    					   ->join('user u ON u.id=w.user_id')
    					   ->field('user_id,sum(download) down,u.username,u.headimg')
    					   ->group('user_id')
    					   ->order('down desc')
    					   //->limit(10)
    					   ->select();
    		//var_dump($order);exit;
    		$work = $model->alias('w')
    					  ->join('user u ON w.user_id=u.id')
    					  ->field('w.id w_id,works,download,favor,u.id u_id,u.username,u.headimg')
    					  ->where('flag=2')
    					  ->order('w.id desc')
    					  //->limit(30)
    					  ->select();
    		//循环获得首页图片展示的路径（默认为第一张）
    		$num = count($work);
    		for($i=0;$i<$num;$i++){
    			$url = get_url($work[$i]['works']);
    			$work[$i]['works'] = $url[0];
    		}
    		//var_dump($work);exit;
    		
    		$this->assign('order',$order);
    		$this->assign('work',$work);
    	}
    	if(IS_POST){
    		//$cate_id = $_POST['cate_id'];
    		$model = M('work');
    		$where['workname'] = array("like","%".I('post.key')."%");
    		$where['username'] = array("like","%".I('post.key')."%");
    	}	
        $this->display();
    }

    /**
     * webuploader 上传文件
     */
    public function ajax_upload(){
        // 根据自己的业务调整上传路径、允许的格式、文件大小
        ajax_upload('Uploads/img/');
    }
 
    /**
     * webuploader 上传demo
     */
    public function webuploader(){
        // 如果是post提交则显示上传的文件 否则显示上传页面
        if(IS_POST){
        	//post_upload('Uploads/img/');
            p($_POST);die;
        }else{
            $this->display();
        }
    }
}

    



