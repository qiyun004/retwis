<?php 

include('./lib.php');
include('./header.php');

if(!isLogin()){
	header('Location:index.php');
	exit;
}


$r = connredis();
$newuserlist = array();
$newuserlist = $r->sort('newuserlink',array('sort'=>'desc','get'=>'user:userid:*:username'));


?>
<div id="navbar">
<a href="index.php">主页</a>
| <a href="timeline.php">热点</a>
| <a href="logout.php">退出</a>
</div>
</div>
<h2>热点</h2>
<i>最新注册用户(redis中的sort用法)</i><br>
<div>
<?php foreach($newuserlist as $u) { ?>
<a class="username" href="profile.php?u=<?php echo $u;?>"><?php echo $u;?></a>
<?php } ?>
 </div>

<br><i>最新的50条微博!</i><br>
<div class="post">
<a class="username" href="profile.php?u=test">test</a>
world<br>
<i>22 分钟前 通过 web发布</i>
</div>

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
hello<br>
<i>22 分钟前 通过 web发布</i>
</div>
<?php include('./footer.php');?>
