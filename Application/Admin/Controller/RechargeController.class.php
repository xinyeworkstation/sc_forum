<?php
namespace Admin\Controller;
use Admin\Controller;
/**
 * 文章管理
 */
class RechargeController extends BaseController
{
    /**
     * 文章列表
     * @return [type] [description]
     */
    public function index($key = "")
    {
        if ($key == "") {
            $model = M('recharge');
        } else {
            $where['username'] = array('like', "%$key%");
            //$where['content'] = array('like', "%$key%");
            $model = M('recharge')->where($where);
            /**
             * $where['category.title'] = array('like',"%$key%");
             * $where['_logic'] = 'or';
             */
        }

        $count = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Extend\Page($count, 15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $post1 = $model->limit($Page->firstRow . ',' . $Page->listRows)->JOIN('user ON  user.id = recharge.user_id  ')->where($where)->order('recharge.id DESC')->field('recharge.money,recharge.time,recharge.id,user.username,recharge.order_number')->select();
        //print_r($post1);
        //$post = $model->limit($Page->firstRow . ',' . $Page->listRows)->where($where)->order('id DESC')->select();
        $this->assign('model', $post1);
        $this->assign('page', $show);
        $this->display();
    }




    public function delete($id)
    {
        $model = M('recharge');
        $result = $model->where("id=".$id)->delete();
        if($result){
            $this->success("删除成功", U('recharge/index'));
        }else{
            $this->error("删除失败");
        }
    }
}