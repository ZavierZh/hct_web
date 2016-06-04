<?php if (!defined('THINK_PATH')) exit();?><dl>
	<dt>热门博文</dt>
	<?php if(is_array($hotblog)): foreach($hotblog as $key=>$v): ?><dd>
			<a href="<?php echo U('Blog/Show/index',array('id'=>$v['id']));?>"><?php echo ($v["title"]); ?></a>
		</dd><?php endforeach; endif; ?>
</dl>

<dl>
	<dt>最新发布</dt>
	<?php if(is_array($newblog)): foreach($newblog as $key=>$v): ?><dd>
			<a href="<?php echo U('Blog/Show/index',array('id'=>$v['id']));?>"><?php echo ($v["title"]); ?></a>
		</dd><?php endforeach; endif; ?>
</dl>