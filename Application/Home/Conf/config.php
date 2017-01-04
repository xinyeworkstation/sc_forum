<?php
return array(
    //主题静态文件路径
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__.'/Application/'.MODULE_NAME.'/View/' . 'Public/static',
        '__PUBLIC__' => __ROOT__.'/Public'
    ),
    //CSRF
    'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
    //是否开启模板布局 根据个人习惯设置
    'LAYOUT_ON'=>false,
    'URL_MODEL'             =>2,


    // 配置邮件发送服务器
    'MAIL_HOST' =>' smtp.163.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'13245002606@163.com',//你的邮箱名
    'MAIL_FROM' =>'13245002606@163.com',//发件人地址
    'MAIL_FROMNAME'=>'阳嘉兴',//发件人姓名
    'MAIL_PASSWORD' =>'yangjiaxing521',//邮箱密码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件

);
