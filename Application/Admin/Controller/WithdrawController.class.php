<?php
namespace Admin\Controller;
use Admin\Controller;
/**
 * 分类管理
 */
class WithdrawController extends BaseController
{
    /**
     * 文章列表
     * @return [type] [description]
     */
    public function index($key = "")
    {
        if ($key == "") {
            $model = M('withdraw');
        } else {
            $where['username'] = array('like', "%$key%");
            //$where['content'] = array('like', "%$key%");
            $model = M('withdraw')->where($where);
            /**
             * $where['category.title'] = array('like',"%$key%");
             * $where['_logic'] = 'or';
             */
        }

        $count = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Extend\Page($count, 15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $post1 = $model->limit($Page->firstRow . ',' . $Page->listRows)->JOIN('user ON  user.id = withdraw.user_id  ')->where($where)->order('withdraw.flag,withdraw.time DESC')->field('withdraw.money,withdraw.time,withdraw.flag,withdraw.id,user.username')->select();
        //print_r($post1);
        //$post = $model->limit($Page->firstRow . ',' . $Page->listRows)->where($where)->order('id DESC')->select();
        $this->assign('model', $post1);
        $this->assign('page', $show);
        $this->display();
    }




    public function update($id)
    {
        $model = M('withdraw');
        $user['flag'] = '1';
        $result = $model->where("id=".$id)->save($user);
        if($result){
            $this->success("提现成功o(*￣▽￣*)ブ", U('withdraw/index'));
        }else{
            $this->error("提现失败！");
        }
    }
}