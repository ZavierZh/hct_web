<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<script type="text/javascript" src="/thinkphp/Public/admin/Js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/thinkphp/Public/admin/Js/index.js"></script>
<link rel="stylesheet" href="/thinkphp/Public/admin/Css/public.css" />
<link rel="stylesheet" href="/thinkphp/Public/admin/Css/index.css" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<base target="iframe"/>
<head>
</head>
<body>
	<div id="top"><!--
		<div class="menu">
			<a href="#">选择按钮</a>
			<a href="#">选择按钮</a>
			<a href="#">选择按钮</a>
			<a href="#">选择按钮</a>
			<a href="#">选择按钮</a>
		</div>-->
		<div class="exit">
			<span><?php echo ($name); ?></span>&nbsp;&nbsp;&nbsp;
			<a href="<?php echo U('Admin/Login/loginout');?>" target="_self">退出</a>
		</div>
	</div>
	<div id="left">
<!-- 		<dl>
			<dt>xx</dt>
			<dd><a href="#">xx</a></dd>
			<dd><a href="#">xx</a></dd>
		</dl> -->
		<dl>
			<dt>博文管理</dt>
			<dd><a href="<?php echo U('Admin/Blog/index');?>">博文列表</a></dd>
			<dd><a href="<?php echo U('Admin/Blog/addBlog');?>">添加博文</a></dd>
			<dd><a href="<?php echo U('Admin/Blog/trach');?>">回收站</a></dd>
			<dd><a href="<?php echo U('Admin/Blog/EmptyTrach');?>">清空回收站</a></dd>

		</dl>
		<dl>
			<dt>属性管理</dt>
			<dd><a href="<?php echo U('Admin/Attribute/index');?>">属性列表</a></dd>
			<dd><a href="<?php echo U('Admin/Attribute/addAttr');?>">添加属性</a></dd>

		</dl>
		<dl>
			<dt>分类管理</dt>
			<dd><a href="<?php echo U('Admin/Category/index');?>">分类列表</a></dd>
			<dd><a href="<?php echo U('Admin/Category/addCate');?>">添加分类</a></dd>
		</dl>
		<dl>
			<dt>系统设置</dt>
			<dd><a href="">用户审核</a></dd>
			<dd><a href="#">个人设置</a></dd>
			<dd><a href="#">属性</a></dd>
		</dl>
		<dl>
			<dt>RBAC</dt>
			<dd><a href="<?php echo U('Admin/Rbac/userlist');?>">用户列表</a></dd>
			<dd><a href="<?php echo U('Admin/Rbac/rolelist');?>">角色列表</a></dd>
			<dd><a href="<?php echo U('Admin/Rbac/nodelist');?>">节点列表</a></dd>
			<dd><a href="<?php echo U('Admin/Rbac/addUser');?>">添加用户</a></dd>
			<dd><a href="<?php echo U('Admin/Rbac/addRole');?>">添加角色</a></dd>
			<dd><a href="<?php echo U('Admin/Rbac/addNode');?>">添加节点</a></dd>
		</dl>

	</div>
	<div id="right">
		<iframe name="iframe" src="<?php echo U('Admin/Blog/index');?>"></iframe>
	</div>
</body>
</html>