<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index($key=""){
        $model = M('work');
        //作者前十排行榜
        $order = $model->alias('w')
                       ->join('user u ON u.id=w.user_id')
                       ->field('user_id,sum(download) down,u.username,u.headimg')
                       ->group('user_id')
                       ->order('down desc')
                       ->limit(10)
                       ->select();
        $num = count($order);
        for($i=0;$i<$num;$i++){
            $order[$i]['username'] = $this->cut_name($order[$i]['username'],15);
        }
        //查询已经审核通过的作品
        $where['flag'] = '1';
        $count = $model->where($where)->count();
        $Page = new \Extend\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数(30)
        $show = $Page->show();// 分页显示输出
    	
    		if($_GET){
          //var_dump($_GET);exit;
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
              $count = $model->where($where)->count();
              $Page = new \Extend\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数(30)
              $show = $Page->show();// 分页显示输出
              $work = $model->alias('w')
                            ->join('user u ON w.user_id=u.id')
                            ->join('category c ON w.cate_id=c.id')
                            ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg,catename')
                            ->where($where)
                            ->order('w.id desc')
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->select();
          }
          if($_GET['user_id']){
              $where['user_id'] = I('get.user_id');
              $count = $model->where($where)->count();
              $Page = new \Extend\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数(30)
              $show = $Page->show();// 分页显示输出
              $work = $model->alias('w')
                            ->join('user u ON w.user_id=u.id')
                            ->join('category c ON w.cate_id=c.id')
                            ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg,catename')
                            ->where($where)
                            ->order('w.id desc')
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->select();
          }
          if(!($_GET['search'] || $_GET['id'] || $_GET['user_id']) && $_GET['p']) {
             $work = $model->alias('w')
                           ->join('user u ON w.user_id=u.id')
                           ->join('category c ON w.cate_id=c.id')
                           ->field('w.id w_id,works,workname,download,favor,u.id u_id,u.username,u.headimg,catename')
                           ->where($where)
                           ->order('w.id desc')
                           ->limit($Page->firstRow.','.$Page->listRows)
                           ->select();
          }
        
      }else{//显示首页
          //var_dump($order);exit;
          if(!$key==""){//按用户要求做查询
            $where['_string'] = "workname like '%{$key}%' or username like '%{$key}%'";
            //$where['workname'] = array("like","%{$key}%");
            //$where['username'] = array("like","%{$key}%");
            $count = $model->alias('w')
                        ->join('user u ON w.user_id=u.id')
                        ->join('category c ON w.cate_id=c.id')
                        ->field('w.id w_id,works,workname,download,favor,u.id u_id,username,u.headimg,catename')
                        ->where($where)
                        ->order('w.id desc')
                        ->limit($Page->firstRow.','.$Page->listRows)
                        ->count();
            $Page = new \Extend\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数(30)
            $show = $Page->show();// 分页显示输出
            $this->assign('count',$count);
            
          }
          $work = $model->alias('w')
                        ->join('user u ON w.user_id=u.id')
                        ->join('category c ON w.cate_id=c.id')
                        ->field('w.id w_id,works,workname,download,favor,u.id u_id,username,u.headimg,catename')
                        ->where($where)
                        ->order('w.id desc')
                        ->limit($Page->firstRow.','.$Page->listRows)
                        ->select();
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