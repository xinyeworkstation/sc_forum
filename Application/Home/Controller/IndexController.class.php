<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
        $model = M('work');
        $order = $model->alias('w')
                       ->join('user u ON u.id=w.user_id')
                       ->field('user_id,sum(download) down,u.username,u.headimg')
                       ->group('user_id')
                       ->order('down desc')
                       //->limit(10)
                       ->select();
        $num = count($order);
        for($i=0;$i<$num;$i++){
            $order[$i]['username'] = $this->cut_username($order[$i]['username']);
        }

    	if(!IS_POST){
    		
    		//var_dump($order);exit;
    		$work = $model->alias('w')
    					  ->join('user u ON w.user_id=u.id')
    					  ->field('w.id w_id,works,download,favor,u.id u_id,u.username,u.headimg')
    					  ->where('flag=1')
    					  ->order('w.id desc')
    					  ->limit(30)
    					  ->select();
    		//循环获得首页图片展示的路径（默认为第一张）
    		$num = count($work);
    		for($i=0;$i<$num;$i++){
    			$url = get_url($work[$i]['works']);
    			$work[$i]['works'] = $url[0];
                $work[$i]['username'] = $this->cut_username($work[$i]['username']);
    		}
    		//var_dump($work);exit;
            
    	   
    		
    		$this->assign('work',$work);
    	}
    	if(IS_POST){
    		//$cate_id = $_POST['cate_id'];
    		//var_dump($_POST);var_dump($_GET);exit;
    		//$where['workname'] = array("like","%".I('post.key')."%");
    		//$where['username'] = array("like","%".I('post.key')."%");
            $key = I('post.key');
            $where['_string'] = "workname like '%{$key}%' or username like '%{$key}%'";
            $where['flag'] = '1';
            $work = $model->alias('w')
                          ->join('user u ON w.user_id=u.id')
                          ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg')
                          ->where($where)
                          ->order('w.id desc')
                          ->limit(30)
                          ->select();
            //循环获得首页图片展示的路径（默认为第一张）
            $num = count($work);
            for($i=0;$i<$num;$i++){
                $url = get_url($work[$i]['works']);
                $work[$i]['works'] = $url[0];
                $work[$i]['username'] = $this->cut_username($work[$i]['username']);
            }
            //echo $model->getLastSql();exit;
    	}

        if($_GET){
            //var_dump($_GET);exit;
            switch (I('get.search')) {
                case '1':
                    # code...
                    break;
                case '2':
                    $work = $model->alias('w')
                          ->join('user u ON w.user_id=u.id')
                          ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg')
                          ->where($where)
                          ->order('download desc')
                          ->limit(30)
                          ->select();
                    break;
                case '3':
                    $work = $model->alias('w')
                          ->join('user u ON w.user_id=u.id')
                          ->field('w.id w_id,works,workname,wtime,download,favor,u.id u_id,u.username,u.headimg')
                          ->where($where)
                          ->order('wtime desc')
                          ->limit(30)
                          ->select();
                    break;
                default:
                    # code...
                    break;
            }
        }
        $this->assign('order',$order);
        $this->assign('work',$work);	
        $this->display();
    }

    private function cut_username($username){
        $username = substr($username, 0,15);
        return $username;
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
