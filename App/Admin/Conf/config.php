<?php
return array(
	//'配置项'=>'配置值'
//	'MODULE_DENY_LIST' => array('Commom','Runtime','Admin'),
		'DB_NAME' => 'test_thinkphp01',
		'DB_PREFIX' => 'think_',
		//超级管理员
// 		'TMPL_PARSE_STRING'=>array(
// //				'__PUBLIC__' => ,
// 				),
		'RBAC_SUPERADMIN' => 'admin', //超级管理员名称
		'ADMIN_AUTH_KEY' => 'superadmin',  //超级管理员识别
		'USER_AUTH_ON' => true, 	//是否开启验证
		'USER_AUTH_TYPE' => 1, 		//验证类别(1: 登陆验证 ,2 :实时验证)
		'USER_AUTH_KEY' => 'uid', 	//用户认证识别号
		'NOT_AUTH_MODULE' => 'index', 	//无需认证的控制器
		'NOT_AUTH_ACTION' => 'index',	//无需验证的方法
		'RBAC_ROLE_TABLE' => 'think_role', //角色表名称
		'RBAC_USER_TABLE' => 'think_role_user', 	//角色和用户的中间表名称
		'RBAC_ACCESS_TABLE' => 'think_access', 	//权限表名称
		'RBAC_NODE_TABLE' => 'think_node', 		//节点表名称
//		'TMPL_TEMPLATE_SUFFIX' => '',
);