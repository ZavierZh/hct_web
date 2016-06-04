<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>index</title>
	<link rel="stylesheet" href="/thinkphp/Public/blog/Css/common.css" />
	<script type="text/JavaScript" src='/thinkphp/Public/blog/Js/jquery-1.7.2.min.js'></script>
	<script type="text/JavaScript" src='/thinkphp/Public/blog/Js/common.js'></script>
	<link rel="stylesheet" href="/thinkphp/Public/blog/Css/index.css" />
</head>
<body>
<!--头部-->
	<div class='top-list-wrap'>
		<div class='top-list'>
		</div>
	</div>
	<div class='top-search-wrap'>
		<div class='top-search'>
			<div href="" class='logo'>
				<img src="/thinkphp/Public/common/Images/logo.png"/>
			</div>
			<div class='search-wrap'>
				<form action="" method='get'>
					<input type="text" name='keyword' class='search-content'/>
					<input type="submit" name='search' value='搜索'/>
				</form>
			</div>
		</div>
	</div>
	<!--
		//这种要不了,和index方法冲突的变量名
		$nav_cate = M('cate')->order('sort')->select();
    	$nav_cate=node_merge($nav_cate);
	-->
	<?php W('Common/navigation');?>



<!--主体-->
	<div class='main'>
		<div class='main-left'>
			<p>后盾博文</p>
			<?php if(is_array($cate)): foreach($cate as $key=>$v): ?><dl>
					<dt><?php echo ($v["name"]); ?><a href="<?php echo U('Blog/List/index',array('id'=>$v['id']));?>"> 更多&gt;&gt;</a></dt>
					
					<?php if(is_array($v["blog"])): foreach($v["blog"] as $key=>$value): ?><dd>
							<a href="<?php echo U('Blog/Show/index',array('id'=>$value['id']));?>"><?php echo ($value["title"]); ?></a>
							<span><?php echo date('m-d',$value.time);?></span>
						</dd><?php endforeach; endif; ?>
				</dl><?php endforeach; endif; ?>
		</div>
	<!--主体右侧-->
		<div class='main-right'>
	<?php W('Common/HotBlog');?>

	<dl>
		<dt>其它链接</dt>
		<dd>
			<a href="">xx网</a>
		</dd>
		<dd>
			<a href="">xxx论坛</a>
		</dd>
		<dd>
			<a href="">xxx学习社区</a>
		</dd>
	</dl>
</div>
	</div>
 	<div class='bottom'>
		<div></div>
	</div>
</body>
</html>