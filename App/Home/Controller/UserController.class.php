<?php
namespace Home\Controller;
use Think\Controller;


class UserController extends Controller{
	public function index(){
//		echo $_GET['id'];
		echo I("get.id");
	}
	
}