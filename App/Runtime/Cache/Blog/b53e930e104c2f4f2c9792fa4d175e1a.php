<?php if (!defined('THINK_PATH')) exit();?><div class='top-nav-wrap'>
	<ul class='nav-lv1'>
		<li class='nav-lv1-li'>
			<a href="<?php echo U('/Blog/Index/index');?>" class='top-cate'>博客首页</a>
		</li>
		<?php if(is_array($nav_cate)): foreach($nav_cate as $key=>$v): ?><li class='nav-lv1-li'>
				<a href="<?php echo U('Blog/List/index',array('id'=>$v['id']));?>" class="top-cate"><?php echo ($v["name"]); ?></a>
				<?php if(!empty($v['child'])): ?><ul>
					<?php if(is_array($v["child"])): foreach($v["child"] as $key=>$two): ?><li>
							<a href="<?php echo U('Blog/List/index',array('id'=>$v['id']));?>"><?php echo ($two["name"]); ?></a>
						</li><?php endforeach; endif; ?>
					</ul><?php endif; ?>
			</li><?php endforeach; endif; ?>
	</ul>
</div>