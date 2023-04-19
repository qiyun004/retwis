<?php

/*
注册用户
set user:userid:1:username lili
set user:userid:1:password 123456

set user:username:lili:userid 1

userid生成
incr global:userid

具体步骤：
1、接收post参数，判断用户名、密码是否完整
2、连接redis，查询该用户名，判断是否存在
3、写入redis
4、登录操作


*/
include('./lib.php');
include('./header.php');


$username = P('username');
$password = P('password');
$password2 = P('password2');

//if(!$username||!$passwrod||!$password2){
if(!$username){
	error('请输入完整注册信息');
}
//判断密码是否一致
if($password !== $password2){
	error('2次密码不一样');
}

//连接redis
$r = connredis();

//查询用户名是否已经注册
if($r->get('user:username:'.$username.':userid')){
	error('用户名已注册');
}


//获取userid
$userid = $r->incr('global:userid');

$r->set('user:userid:'.$userid.':username',$username);
$r->set('user:userid:'.$userid.':password',$password);
$r->set('user:username:'.$username.':userid',$userid);








include('./footer.php');
