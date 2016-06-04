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
			<th width="5%">ID</th>
			<th width="15%">用户名</th>
			<th width="20%">用户昵称</th>
			<th>E-mail</th>
			<th width="10%">电话</th>
			<th>状态</th>
			<th>用户所在组</th>
			<th width="30%">操作</th>
		</tr>
		<?php if(is_array($user)): foreach($user as $key=>$v): ?><tr>
				<td><?php echo ($v["id"]); ?></td>
				<td><?php echo ($v["name"]); ?></td>
				<td><?php echo ($v["nickname"]); ?></td>
				<td><?php echo ($v["email"]); ?></td>
				<td><?php echo ($v["phone"]); ?></td>
				<td>
					<?php if($v['status']): ?>开启<?php else: ?>关闭<?php endif; ?>
				</td>
				<td>
					<?php if($v['name'] == C('RBAC_SUPERADMIN')): ?>超级管理员
					<?php else: ?>
						<ul>
							<?php if(is_array($v["role"])): foreach($v["role"] as $key=>$n): ?><li><?php echo ($n["name"]); ?>(<?php echo ($n["remark"]); ?>)</li><?php endforeach; endif; ?>
						</ul><?php endif; ?>
				</td>
				<td>
					[<a href="">修改</a>]
					[<a href="">删除</a>]
				</td>
			</tr><?php endforeach; endif; ?>
	</table>
</body>
</html>