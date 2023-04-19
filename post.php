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
$r->set('post:postid:'.$postid.':userid',$user['userid']);
$r->set('post:postid:'.$postid.':time',time());
$r->set('post:postid:'.$postid.':content',$content);

header('location:home.php');
exit;




include('./footer.php');
