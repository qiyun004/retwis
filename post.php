<?php

include('./lib.php');
include('./header.php');


/*
incr global:postid
set post:postid:$postid:time timestamp
set post:postid:$postid:userid $user
set post:postid:$postid:content $content

1、判断是否登录
2、接收post内容
3、set redis

*/

if(($user = isLogin()) == false){
	header('location: index.php');
	exit;
}	


$content = P('status');
if(!$content){
	error('请填写内容');
}

$r = connredis();
$postid = $r->incr('global:postid');

//第二版：使用hash存储微博
$r->hmset('post:postid:'.$postid,array(
'userid'=>$user['userid'],
'username'=>$user['username'],
'time'=>time(),
'content'=>$content
));
//把自己发的微博维护一个有序集合里，只要前20个,用于粉丝拉取
$r->zadd('starpost:userid:'.$user['userid'],$postid,$postid);
if($r->zcard('starpost:userid:'.$user['userid'])>20){
	$r->zremrangebyrank('starpost:userid:'.$user['userid'],0,0);
}

//把自己的微博id放到一个链表中，存放1000个，自己看微博用
//大于1000的旧微博，放到mysql里
$r->lpush('mypost:userid:'.$user['userid'],$postid);
if($r->llen('mypost:userid:'.$user['userid'])>1000){
	$r->rpoplpush('mypost:userid:'.$user['userid'],'global:store');
}
header('location:home.php');
exit;


include('./footer.php');
