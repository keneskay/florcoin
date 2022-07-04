<?php
	include("cfg.php");

	$data = authcheck($cfg,$_GET['uid'],$_GET['hash']);

	if ($_GET['find'] && $_GET['uid']) {
		$req = "SELECT * FROM `users` WHERE `id` LIKE '%".$_GET['find']."%' OR `other` LIKE '%".$_GET['find']."%' LIMIT 25";
		$data = mysqli_fetch_all(mysqli_query($cfg['dbl'],$req));
		foreach ($data as $key => $value) {
			$value[5] = json_decode($value[5],true);
			$generated .= '
				<div class="list-item row">
					<img class="item-img" src="'.$value[5]['icon'].'">
					<div class="item-info">
						<p class="item-uname">'.$value[5]['name'].'</p>
						<div id="transfer-'.$value[0].'" class="item-action-btn">Перевести</div>
					</div>
				</div>
			';
		}
		$generated .= '
			<script>
				$(".item-action-btn").click(function() {
					let uri = "transfer.php?hash='.$_GET['hash'].'&uid='.$_GET['uid'].'&transfer_to="+$(this).attr("id")+"&transfer_sum="+$(".item-score-input").val();
					$.ajax({
						url: uri,
						cache: false,
						success: function(html){
							if (html != "fail") {
								$(".overlay-active").click();
								success_notify();
							} else {
								fail_notify();
							}
						}
					});
				});
			</script>
		';
		echo $generated;
	} else if ($_GET['transfer_sum'] && $_GET['transfer_to'] && $_GET['uid']) {
		$_GET['transfer_to'] = explode("-",$_GET['transfer_to'])[1];
		$req = "SELECT * FROM `users` WHERE `id`='".$_GET['uid']."'";
		$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));
		if ($data['score'] >= $_GET['transfer_sum'] && $_GET['transfer_sum'] > 0 && $_GET['transfer_sum'] < 10000000000) {
			$tdata = date("Y.m.d H:i:s")."|".$_GET['uid']."|".$_GET['transfer_to']."|".$_GET['transfer_sum'].",";
			$req = "UPDATE `users` SET `score`=`score`-'".$_GET['transfer_sum']."' WHERE `id`='".$_GET['uid']."'";
			mysqli_query($cfg['dbl'],$req);
			$req = "UPDATE `users` SET `score`=`score`+'".$_GET['transfer_sum']."' WHERE `id`='".$_GET['transfer_to']."'";
			mysqli_query($cfg['dbl'],$req);
			$req = "INSERT INTO `transactions`(`date`, `fromid`, `toid`, `amount`) VALUES ('".time()."','".$_GET['uid']."','".$_GET['transfer_to']."','".$_GET['transfer_sum']."')";
			mysqli_query($cfg['dbl'],$req);
		} else {
			echo 'fail';
		}
	}
?>