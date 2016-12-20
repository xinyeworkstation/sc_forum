<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
    	
        $this->display();
    }
<<<<<<< HEAD

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
=======
    

}
>>>>>>> 3dbdfca903cfcadaae3fcd6b2bbd38c6d80f4bce
