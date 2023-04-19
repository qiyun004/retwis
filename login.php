<?php

include('./lib.php');
include('./header.php');


/*

登录页面
1、接收post，判断信息完整性
2、查询用户名是否存在
3、查询密码是否匹配
4、设置cookie

*/

if(isLogin() != false){
	header('location:home.php');
	exit;
}



$username = P('username');
$password = P('password');

if(!$username || !$password){
	error('请输入完整用户名和密码');
}
$r = connredis();
$userid = $r->get('user:username:'.$username.':userid');
if(!$userid){
	error('用户名不存在');

}

$realpass = $r->get('user:userid:'.$userid.':password');

if($password !== $realpass){
	error('密码不正确');
}

//设置cookie，登录成功
setcookie('username',$username);
setcookie('userid',$userid);

header('location:home.php');
