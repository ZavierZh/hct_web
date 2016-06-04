<?php
return array(
	//'配置项'=>'配置值'
	//禁止模块访问的宏
//	'MODULE_DENY_LIST' => array('Commom','Runtime','Admin'),

//	'MODULE_DENY_LIST'   => array('Common','Blog','Admin','Home'),
	//允许模块访问
	'MODULE_ALLOW_LIST' => array('Home','Admin','Blog'),
	'DEFAULT_MODULE'       =>    'Blog',  // 默认模块
// 		'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
// 		'DEFAULT_ACTION'        =>  'index', // 默认操作名称
		
		//模块映射的模块必须使用小写定义
	//	'URL_MODULE_MAP'       =>    array('test'=>'admin'),
		
	//单模块,只允许一个模块
	//'MULTI_MODULE' => false,
		
		'DB_NAME' => 'test_thinkphp01',
		'DB_PREFIX' => 'think_',
	//thinkphp的调试工具
		'SHOW_PAGE_TRACE' => true,
// 		数据库字段缓存 调试模式自动关闭
	//	'DB_FIELDS_CACHE'
//路由无效果!!!!
// 		'URL_ROUTER_ON'=> true,
// 		'URL_ROUTER_RULES' => array(
// 				'u' => 'Index/index',
// 				),		
// 		'URL_MAP_RULES'=>array(
// 				'my.html' => '/index.php/Blog/Index/index.html',
// 		)
		
		//静态缓存
		'HTML_CACHE_ON' => true,
		//全局缓存过期时间
		'HTML_CACHE_TIME' => 60,
		//缓存后缀
		'HTML_FILE_SUFFIX' =>'.html',
		
		'HTML_CACHE_RULES' => array(
				'Index:index' => array('{:module}_{:controller}_{:action}',60),
		),
		
		
);