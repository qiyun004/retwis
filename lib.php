<?php

//获取post参数
function P($key){
  return $_POST[$key];
}

//获取get参数
function G($key){
  return $_GET[$key];
}

//报错函数
function error($msg){
	echo "<div>";
	echo $msg;
	echo "</div>";
	include('./footer.php');
	exit;
}
//连接redis
function connredis(){
	static $r = null;
	if($r !== null){
	  return $r;
	}
	$r = new Redis();
	$r->connect('127.0.0.1');
	return $r;
}
//判断用户是否登录
function isLogin(){
	if(!$_COOKIE['userid'] || !$_COOKIE['username']){
		return false;
	}	
	return array('userid'=>$_COOKIE['userid'],'username'=>$_COOKIE['username']);
}
