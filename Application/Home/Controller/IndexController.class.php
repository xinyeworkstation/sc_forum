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
    		if($_GET){
            //var_dump($_GET);exit;
                $where['flag'] = '1';
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
                              //var_dump($work);exit;
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
                if($_GET['id']){
                    $id = I('get.id');//传过来的模块id
                    $where['cate_id'] = $id;
                    $work = $model->alias('w')
                                  ->join('user u ON w.user_id=u.id')
                                  ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg')
                                  ->where($where)
                                  ->order('w.id desc')
                                  ->limit(30)
                                  ->select();
                }
                
            }else{
                //var_dump($order);exit;
                $work = $model->alias('w')
                              ->join('user u ON w.user_id=u.id')
                              ->field('w.id w_id,works,download,favor,u.id u_id,u.username,u.headimg')
                              ->where('flag=1')
                              ->order('w.id desc')
                              ->limit(30)
                              ->select();
            }
    		
    	}
    	if(IS_POST){
            
    		//$cate_id = $_POST['cate_id'];
    		//var_dump($_POST);var_dump($_GET);exit;
    		//$where['workname'] = array("like","%".I('post.key')."%");
    		//$where['username'] = array("like","%".I('post.key')."%");
            
            $key = $_POST['key'];
            //echo $key;
            $name = $_POST['name'];
            echo $name;
            $where['_string'] = "workname like '%{$key}%' or username like '%{$key}%'";
            $where['flag'] = '1';
            /*$name = I('post.name');
            if(!$name == '全部'){
                $where['catename'] = $name;
            }*/
             
            //echo $key;
            //echo $where['catename'];
            $work = $model->alias('w')
                          ->join('user u ON w.user_id=u.id')
                          //->join('category c ON w.cate_id=c.id')
                          ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg')
                          ->where($where)
                          ->order('w.id desc')
                          ->limit(30)
                          ->select();
            if(empty($work)){
                echo '没有找到您要查询的作品';
            }
            //echo $model->getLastSql();exit;
    	}

        
        $work = $this->new_work($work);
        //var_dump($work);exit;
        $this->assign('order',$order);
        $this->assign('work',$work);	
        $this->display();
    }


    function index_model(){
        $this->display();
    }

    //截取用户名长度
    private function cut_username($username){
        $username = substr($username, 0,15);
        return $username;
    }

    //将查询出来的作品做处理得到新的数组
    private function new_work($work){
        //循环获得首页图片展示的路径（默认为第一张）
        $num = count($work);
        for($i=0;$i<$num;$i++){
            $url = get_url($work[$i]['works']);
            $work[$i]['works'] = $url[0];
            $work[$i]['username'] = $this->cut_username($work[$i]['username']);
        }
        return $work;
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
