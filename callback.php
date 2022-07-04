<?php
	include("cfg.php");
	http_response_code(200);
	if ($_GET['secret'] == '4spFC48vGfRXUkF7PWH4JkfxTWVtDVc8') {
		$data = json_decode(file_get_contents("php://input"),true);
		$req = "SELECT * FROM `donates` WHERE `id`='".$data['id']."'";
		$dbdata = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));
		if ($data['fromId'] == $data['from_id'] && $data['toId'] == $data['to_id'] && (time() - 5) <= $data['created_at'] && !$dbdata['sum']) {
			$req = "UPDATE `users` SET `score`=`score`+'".($data['amount'] / ($cfg['vc_exchange_rate'] * 1000))."' WHERE `id`='".$data['from_id']."'";
			mysqli_query($cfg['dbl'],$req);
			$req = "INSERT INTO `donates`(`id`, `fromid`, `sum`) VALUES ('".$data['id']."','".$data['from_id']."','".($data['amount']/($cfg['vc_exchange_rate'] * 1000))."')";
			mysqli_query($cfg['dbl'],$req);
			$req = "INSERT INTO `transactions`(`date`, `fromid`, `toid`, `amount`) VALUES ('".time()."','1','".$data['from_id']."','".($data['amount']/($cfg['vc_exchange_rate'] * 1000))."')";
			mysqli_query($cfg['dbl'],$req);
			echo "ok";
		}
	}
?>