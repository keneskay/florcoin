<?php
	include("cfg.php");

	if ($_GET['token'] && $_GET['uid'] && $_GET['hash'] && preg_match("/^[\d]+$/",$_GET['uid'])) {
		$req = "UPDATE `users` SET `token`='".$_GET['token']."' WHERE `id`='".$_GET['uid']."' AND `hash`='".$_GET['hash']."'";
		mysqli_query($cfg['dbl'],$req);
	}
?>