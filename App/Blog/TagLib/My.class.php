<?php 
namespace Blog\TagLib;

use Think\Template\TagLib;

/*
 * 
 * 自定义标签库
 * 
 */

class My extends TagLib{
	
	protected $tags = array(
			'nav' => array(
					'attr' => 'limit,order',
					'close' => 1,//默认1,为闭合标签<b></b> ,非闭合标签:<input/>
					),
			);
	
	public function _nav($attr,$content){
//		parray( $attr);
		$str = <<<EOF
	<?php
		\$cate = M('cate')->order('sort')->select();
    	\$cate=node_merge(\$cate);
    	foreach(\$cate as \$v) :
	?>
EOF;
		$str .= $content;
		$str .= '<?php endforeach;?>';
		return $str;
	}
	
}



?>