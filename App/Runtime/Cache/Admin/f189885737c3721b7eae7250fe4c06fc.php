<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
 	<link rel="stylesheet" href="/thinkphp/Public/admin/Css/public.css" />
 	<title>admin</title>
 </head>
 <body>
 	<table class="table">
 		<tr>
 			<th>ID</th>
 			<th>标题</th>
 			<th>所属分类</th>
 			<th>点击次数</th>
 			<th>发布时间</th>
 			<th>操作</th>
 		</tr>
 		<?php if(empty($blog)): ?></table>
 			<h2 style="text-align:center;margin: 20px;font-size: 3em;">没有数据</h2>
 		<?php else: ?>
	 		<?php if(is_array($blog)): foreach($blog as $key=>$v): ?><tr>
	 				<td><?php echo ($v["id"]); ?></td>
	 				<td>
	 					<?php echo ($v["title"]); ?>
	 					<?php if(is_array($v["attr"])): foreach($v["attr"] as $key=>$value): ?>[<strong style='color:<?php echo ($value["color"]); ?>'><?php echo ($value["name"]); ?></strong>]<?php endforeach; endif; ?>
	 				</td>
	 				<td><?php echo ($v["cate"]); ?></td>
	 				<td><?php echo ($v["click"]); ?></td>
	 				<td><?php echo date('y-m-d H:i',$v['time']);?></td>
	 				<td>
	 					[<a href="">修改</a>]
	 					<?php if(ACTION_NAME == 'index'): ?>[<a href="<?php echo U('Admin/Blog/toTrach',array('id'=>$v[id],'del'=>1));?>">删除</a>]
	 					<?php else: ?>
		 					[<a href="<?php echo U('Admin/Blog/toTrach',array('id'=>$v[id],'del'=>0));?>">恢复</a>]<?php endif; ?>
	 					[<a href="<?php echo U('Admin/Blog/delete',array('id'=>$v[id]));?>">彻底删除</a>]
	 				</td>
	 			</tr><?php endforeach; endif; ?>
 	</table><?php endif; ?>
 </body>
 </html>