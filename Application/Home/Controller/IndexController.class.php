<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
<<<<<<< HEAD
    
    public function index()
    {
=======
    public function index(){
    	$model = M('category');
    	$model2 = M('work');
    	$cate = $model->select();
    	if(IS_POST){
    		$id = $_POST['cate_id'];
    		$work = $model2->alias('w')
    					   ->join('user u ON w.user_id=u.id')
    					   ->field('w.id,workname,works,u.username')
    					   ->where('w.cate_id='.$id)
    					   ->select();
    		$this->assign('work',$work);

    	}
    	$this->assign('cate',$cate);
>>>>>>> 286018c8eba6442bd1ec252fffa2c662759a52bb
        $this->display();
    }

}