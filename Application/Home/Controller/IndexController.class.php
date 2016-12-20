<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
    	if(!IS_POST){
    		$model = M('work');
    		$order = $model->alias('w')
    					   ->join('user u ON u.id=w.user_id')
    					   ->field('user_id,sum(download) down,u.username')
    					   ->group('user_id')
    					   ->order('down desc')
    					   ->limit(10)
    					   ->select();
    		//var_dump($order);exit;
    		$work = $model->alias('w')
    					  ->join('user u ON w.user_id=u.id')
    					  ->join('left join favorite f ON w.id=f.work_id')
    					  ->field('w.id w_id,works,download,u.id u_id,u.username,u.headimg,count(f.work_id) favor')
    					  ->group('f.work_id')
    					  ->select();

    		//var_dump($work);exit;
    		$this->assign('order',$order);
    		$this->assign('work',$work);
    	}	
        $this->display();
    }
    

}