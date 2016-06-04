<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" href="/thinkphp/Public/admin/Css/public.css" />
	<title>admin</title>
</head>
<body>
<form action="<?php echo U('Admin/Category/sortCate');?>" method="post">
	<table class="table">
		<tr>
			<th>ID</th>
			<th>名称</th>
			<th>级别</th>
			<th>排序</th>
			<th>操作</th>
		</tr>
		<?php if(is_array($cate)): foreach($cate as $key=>$v): ?><tr>
				<td><?php echo ($v["id"]); ?></td>
				<td><?php echo ($v["html"]); echo ($v["name"]); ?></td>
				<td><?php echo ($v["level"]); ?></td>
				<td><input type="text" name="<?php echo ($v["id"]); ?>" value="<?php echo ($v["sort"]); ?>"/></td>
				<td>
					[<a href="<?php echo U('Admin/Category/addCate',array('pid'=>$v['id']));?>">添加子分类</a>]
					[<a href="">修改</a>]
					[<a href="">删除</a>]
				</td>
			</tr><?php endforeach; endif; ?>
		<tr>
			<td colspan="5" align="center">
				<input type="submit" value="排序"/>
			</td>
		</tr>
	</table>
	</form>
</body>
</html>