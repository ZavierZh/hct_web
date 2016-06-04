<?php
namespace Common\Controller;
use Think\Controller;

class AdminCommonController extends Controller{
	public  function __construct(){
		parent::__construct();
		header("content-type:text/html;charset=utf-8");
	}
}