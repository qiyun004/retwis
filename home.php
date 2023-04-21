<?php 
include('./lib.php');
include('./header.php');?>

<?php
if( ($user =isLogin()) == false){
        header('location:index.php');
        exit;
}

$r = connredis();
//获取我关注的人
$star = $r->smembers('following:'.$user['userid']);
$star[] = $user['userid'];

$lastpull = $r->get('lastpull:userid:'.$user['userid']);
if(!$lastpull){
	$lastpull = 0;
}
//拉取最新数据
$latest = array();
foreach($star as $s){
	$latest = array_merge($latest,$r->zrangebyscore('starpost:userid:'.$s,$lastpull+1,1<<32-1));
}
sort($latest,SORT_NUMERIC);


//更新lastpull
if(!empty($latest)){
	$r->set('lastpull:userid:'.$user['userid'],end($latest));
}
//循环把latest放到自己主页应该收取的微博链接里
foreach($latest as $l){
	$r->lpush('recivepost:'.$user['userid'],$l);
}
$newposter = $r->sort('recivepost:'.$user['userid'],array('sort'=>'desc'));
//计算自己有多少粉丝和关注多少人
$myfans = $r->scard('follower:'.$user['userid']);
$mystar = $r->scard('following:'.$user['userid']);
?>
<div id="navbar">
<a href="index.php">主页</a>
| <a href="timeline.php">热点</a>
| <a href="logout.php">退出</a>
</div>
</div>
<div id="postform">
<form method="POST" action="post.php">
<?php echo $user['username']; ?>, 有啥感想?
<br>
<table>
<tr><td><textarea cols="70" rows="3" name="status"></textarea></td></tr>
<tr><td align="right"><input type="submit" name="doit" value="Update"></td></tr>
</table>
</form>
<div id="homeinfobox">
<?php echo $myfans;?> 粉丝<br>
<?php echo $mystar;?> 关注<br>
</div>
</div>

<?php foreach($newposter as $postid){
$p = $r->hmget('post:postid:'.$postid,array('userid','username','time','content'));
 ?>


<div class="post">
<a class="username" href="profile.php?u=<?php echo $p['username'];?>"><?php echo $p['username'];?></a> <?php echo $p['content'];?><br>

<i><?php echo formattimeinfo($p['time']);?>前 通过 web发布</i>
</div>
<?php } ?>
<?php include('./footer.php');?>

