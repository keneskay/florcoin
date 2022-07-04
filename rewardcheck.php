<?php
	include("cfg.php");

	$data = authcheck($cfg,$_GET['uid'],$_GET['hash']);

	if ($_GET['uid'] && preg_match("/^[\d]+$/",$_GET['uid'])) {
		$req = "SELECT * FROM `act` WHERE `id`='".$_GET['id']."'";
		$data = json_decode(mysqli_fetch_array(mysqli_query($cfg['dbl'],$req))[1],true);
		if (!stristr($data['users'],$_GET['uid'])) {
			if ($_GET['type'] == 'share' && $_GET['post-id']) {
				$req = "UPDATE `users` SET `score`=`score`+'".$data['sum']."' WHERE `id`='".$_GET['uid']."'";
				mysqli_query($cfg['dbl'],$req);
				$req = "UPDATE `act` SET `enabled`=`enabled`-'1',`users`=CONCAT(`users`,',".$_GET['uid']."') WHERE `id`='".$_GET['id']."'";
				mysqli_query($cfg['dbl'],$req);
			} else if ($_GET['type'] == 'sub') {
				$req = "UPDATE `users` SET `score`=`score`+'".$data['sum']."' WHERE `id`='".$_GET['uid']."'";
				mysqli_query($cfg['dbl'],$req);
				$req = "UPDATE `act` SET `enabled`=`enabled`-'1',`users`=CONCAT(`users`,',".$_GET['uid']."') WHERE `id`='".$_GET['id']."'";
				mysqli_query($cfg['dbl'],$req);
			} else if ($_GET['type'] == 'getSeller' && $_GET['amount'] == 130 && $_GET['id']) {
				$req = "UPDATE `users` SET `role`='seller' WHERE `id`='".$_GET['uid']."'";
				mysqli_query($cfg['dbl'],$req);
				w_log($_GET['extra']);
			} else if ($_GET['type'] == 'getVip' && $_GET['amount'] == 70 && $_GET['id']) {
				$req = "UPDATE `users` SET `role`='vip' WHERE `id`='".$_GET['uid']."'";
				mysqli_query($cfg['dbl'],$req);
				w_log($_GET['extra']);
			}
		} else {
			echo "fail";
			exit();
		}
	}
?>