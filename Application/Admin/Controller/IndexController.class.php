<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends BaseController{

    public function index()
    {
        redirect(U('user/index?level=1'));
    }

}
