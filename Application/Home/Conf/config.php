<?php
return array(
	//'配置项'=>'配置值'
    "IOSHREF"=>"https://itunes.apple.com/cn/app/id1278250760?mt=8",
    "ANFRIODHREF"=>"http://a.app.qq.com/o/simple.jsp?pkgname=com.zrgg.qiangpiao",
     'DB_TYPE'               => 'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'huanxin',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'Rwxtest2017',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'hx_',    // 数据库表前缀
    'DB_PARAMS'          	=>  array(), // 数据库连接参数    
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
    
    "SESS_ACCOUNT"          =>"account",
    "SESS_UID"              =>"uid",
    
    /*亿美短信*/
    "YM_WGURL"				=> 'http://hprpt2.eucp.b2m.cn:8080/sdk/SDKService?wsdl',  					/*亿美短信接口网关*/
    "YM_SERIAL"				=> '8SDK-EMY-6699-SBYNM',  	/*亿美短信接口序列号*/
    "YM_PWD" 				=> '962147',				/*亿美短信接口密码*/
    "YM_SESSION_KEY" 			=> '388031',				/*亿美短信接口session_key,需要去配置*/
    /*短信有效期*/
    "SMSTIME"				=> 900,
);
