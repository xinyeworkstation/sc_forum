<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model{

	protected $_validate = array(
		array('username','require','用户名必须填写！！！'), //默认情况下用正则进行验证
	    array('username','','帐号名称已经存在！！！',0,'unique',1), // 在新增的时候验证username字段是否唯一
	    array('email','email','邮箱格式填写错误！！！'),
	    array('email','','该邮箱已被注册！！！',0,'unique',1), // 在新增的时候验证email字段是否唯一
	    array('repassword','password','确认密码不正确！！！',0,'confirm'), // 验证确认密码是否和密码一致
		);
	 protected $_auto = array (              
	 	array('password','md5',3,'function') , //对password字段在新增和编辑的时候使md5函数处理
	 	);
}