<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
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

	if(!$_COOKIE['authsecret']){
		return false;
	}	
	//global $r;
	$r = connredis();
	$authsecret = $r->get('user:userid:'.$_COOKIE['userid'].':authsecret');
	if($authsecret !=$_COOKIE['authsecret']){
		return false;
	}

	return array('userid'=>$_COOKIE['userid'],'username'=>$_COOKIE['username']);
}
//生成随机数
function randsecret(){
	$str = 'abcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*(';
	return substr(str_shuffle($str),0,16);
}

//格式化时间
function formattimeinfo($time){
	$sec = time() - $time;
	
	if($sec >= 86400){
		return floor($sec/86400).'天';
	}else if($sec >= 3600){
		return floor($sec/3600).'小时';
	}else if($sec >= 60){
		return floor($sec/60).'分钟';
	}else{
		if($sec<10){
			$sec = $sec+1;
			return $sec.'秒';
		}
		return $sec.'秒';
	}

}

