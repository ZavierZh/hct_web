<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" href="/thinkphp/Public/admin/Css/public.css" />
	<title>admin</title>
</head>
<body>
	<table class="table">
		<tr>
			<th width="10%">ID</th>
			<th width="15%">角色名称</th>
			<th width="20%">角色描述</th>
			<th width="10%">状态</th>
			<th width="30%">操作</th>
		</tr>
		<?php if(is_array($role)): foreach($role as $key=>$v): ?><tr>
				<td><?php echo ($v["id"]); ?></td>
				<td><?php echo ($v["name"]); ?></td>
				<td><?php echo ($v["remark"]); ?></td>
				<td>
					<?php if($v['status']): ?>开启<?php else: ?>关闭<?php endif; ?>
				</td>
				<td>
					[<a href="<?php echo U('Admin/Rbac/access',array('rid'=>$v['id']));?>">配置权限</a>]
					[<a href="">修改</a>]
					[<a href="">删除</a>]
				</td>
			</tr><?php endforeach; endif; ?>
	</table>
</body>
</html>