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
            $order[$i]['username'] = $this->cut_name($order[$i]['username'],15);
        }

      $where['flag'] = '1';
      $count = $model->where($where)->count();
      $Page = new \Extend\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(30)
      $show = $Page->show();// 分页显示输出
    	if(!IS_POST){
    		if($_GET){
            //var_dump($_GET);exit;
                //查询已经审核通过的作品
                switch (I('get.search')) {
                    //首页综合排序
                    case '1':
                        # code...
                        break;
                    //首页热门下载
                    case '2':
                        $work = $model->alias('w')
                              ->join('user u ON w.user_id=u.id')
                              ->join('category c ON w.cate_id=c.id')
                              ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg,catename')
                              ->where($where)
                              ->order('download desc')
                              ->limit($Page->firstRow.','.$Page->listRows)
                              ->select();
                              //var_dump($work);exit;
                        break;
                    //首页作品新上传
                    case '3':
                        $work = $model->alias('w')
                              ->join('user u ON w.user_id=u.id')
                              ->join('category c ON w.cate_id=c.id')
                              ->field('w.id w_id,works,workname,wtime,download,favor,u.id u_id,u.username,u.headimg,catename')
                              ->where($where)
                              ->order('wtime desc')
                              ->limit($Page->firstRow.','.$Page->listRows)
                              ->select();
                        break;
                    default:
                        # code...
                        break;
                }
                //按作品版区查询作品
                if($_GET['id']){
                    $id = I('get.id');//传过来的模块id
                    $where['cate_id'] = $id;
                    $work = $model->alias('w')
                                  ->join('user u ON w.user_id=u.id')
                                  ->join('category c ON w.cate_id=c.id')
                                  ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg,catename')
                                  ->where($where)
                                  ->order('w.id desc')
                                  ->limit($Page->firstRow.','.$Page->listRows)
                                  ->select();
                }
                
            }else{
                //var_dump($order);exit;
                $work = $model->alias('w')
                              ->join('user u ON w.user_id=u.id')
                              ->join('category c ON w.cate_id=c.id')
                              ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg,catename')
                              ->where($where)
                              ->order('w.id desc')
                              ->limit($Page->firstRow.','.$Page->listRows)
                              ->select();
            }
    		
    	}

        //按用户要求查询搜索的作品
    	if(IS_POST){
            
    		//$cate_id = $_POST['cate_id'];
    		//var_dump($_POST);var_dump($_GET);exit;
    		//$where['workname'] = array("like","%".I('post.key')."%");
    		//$where['username'] = array("like","%".I('post.key')."%");
            
            $key = I('post.key');
            //echo $key;exit;
            $name = $_POST['name'];
            //$name = I('post.name');
            //echo $name;exit;
            $where['_string'] = "workname like '%{$key}%' or username like '%{$key}%'";
            $where['flag'] = '1';
            //$name = I('post.name');
            /*if(!($name == '全部')){
                $where['catename'] = $name;
            }*/
            $cate['catename'] = $name;
            $cmodel = M('category');
            $cid = $cmodel->where($cate)->find();
            $where['cate_id'] = $cid['id'];
            $work = $model->alias('w')
                          ->join('user u ON w.user_id=u.id')
                          ->join('category c ON w.cate_id=c.id')
                          ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg,catename')
                          ->where($where)
                          ->order('w.id desc')
                          ->limit(30)
                          ->select();
            if(empty($work)){
                echo 0;
            }
            //echo $model->getLastSql();exit;
    	}

        
        $work = $this->new_work($work);
        //var_dump($work);exit;
        $this->assign('order',$order);
        $this->assign('work',$work);
        $this->assign('page',$show);	
        $this->display();
    }


    function index_model(){
        $this->display();
    }

    //截取名字的长度
    private function cut_name($name,$length){
        $name = substr($name, 0,$length);
        return $name;
    }

    //将查询出来的作品做处理得到新的数组
    private function new_work($work){
        //循环获得首页图片展示的路径（默认为第一张）
        $num = count($work);
        for($i=0;$i<$num;$i++){
            $url = get_url($work[$i]['works']);
            $work[$i]['works'] = $url[0];
            $work[$i]['username'] = $this->cut_name($work[$i]['username'],15);
            $work[$i]['workname'] = $this->cut_name($work[$i]['workname'],18);
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
