<?php
return array(
	'random'=>array(//配置在表单中的键名 ,这个会是config[random]
		'title'=>'是否开启随机:',//表单的文字
		'type'=>'radio',		 //表单的类型：text、textarea、checkbox、radio、select等
		'options'=>array(		 //select 和radion、checkbox的子选项
			'1'=>'开启',		 //值=>文字
			'0'=>'关闭',
		),
		'value'=>'1',			 //表单的默认值
	),

	'remote_config' => array(
		'title' => '远程库IP:',
		'type' 	=> 'textarea',
		'value' =>'
db_type:mysqli,
db_host:127.0.0.1,
db_user:root,
db_pwd:root,
db_port:3306,
db_name:test,
db_charset:utf8',
		'tip' => '数据库信息 db_type:类型,db_host:地址,db_user:用户名,db_pwd:密码,db_port:端口号,db_name:数据库名,db_charset:字符集'
	),
);
					