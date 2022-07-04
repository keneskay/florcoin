<?php
	include("cfg.php");

	$req = "SELECT * FROM `users` WHERE `id`='".$_GET['uid']."' AND `hash`='".$_GET['hash']."'";
	$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));
	if ($data['hash'] != $_GET['hash'] || $data['role'] == 'ban') {
		echo "fail";
		exit();
	}

	function list_counter($key) {
		switch ($key) {
			case 0:
				$top = '💥';
				break;
			case 1:
				$top = '💥';
				break;
			case 2:
				$top = '💥';
				break;
			
			default:
				$top = $key + 1;
				break;
		}
		return $top;
	}

	function users_list($cfg,$uids) {
		$uids = explode(",",$uids);
		foreach ($uids as $key => $value) {
			if ($req) {
				$req .= " OR `id`='".$value."'";
			} else {
				$req = "`id`='".$value."'";
			}
		}
		$req = "SELECT * FROM `users` WHERE ".$req." ORDER BY `score` DESC LIMIT 100";
		$data = mysqli_fetch_all(mysqli_query($cfg['dbl'],$req));
		foreach ($data as $key => $value) {
			$userdata = json_decode($value[5],true);
			switch ($value[3]) {
				case 'vip':
					$value[3] = '<span class="role" style="position: absolute; background-color: white; color:#B718FF; border-color:#B718FF;">Царь</span>';
					break;

				case 'seller':
					$value[3] = '<span class="role" style="position: absolute; background-color: white; color:red; border-color:red;">Барыга</span>';
					break;
					
				case 'ur1':
					$value[3] = '<span class="role" style="position: absolute; background-color: white; color:#1825FF; border-color:#1825FF;">x1✯</span>';
					break;
					
				case 'ur2':
					$value[3] = '<span class="role" style="position: absolute; background-color: white; color:#1825FF; border-color:#1825FF;">x2✯</span>';
					break;
					
				case 'ur3':
					$value[3] = '<span class="role" style="position: absolute; background-color: white; color:#1825FF; border-color:#1825FF;">x3✯</span>';
					break;
					
				case 'ur4':
					$value[3] = '<span class="role" style="position: absolute; background-color: white; color:#1825FF; border-color:#1825FF;">x4✯</span>';
					break;
					
				case 'ur5':
					$value[3] = '<span class="role" style="position: absolute; background-color: white; color:#1825FF; border-color:#1825FF;">x5✯</span>';
					break;
					
				case 'ur6':
					$value[3] = '<span class="role" style="position: absolute; background-color: white; color:#1825FF; border-color:#1825FF;">&#11088;x6&#11088;</span>';
					break;
				
				default:
					$value[3] = '';
					break;
			}
			$table .= '
				<a href="https://vk.com/id'.$value[0].'" target="_blank" class="list-item row">
					<p class="item-count">'.list_counter($key).'</p>
					<div class="item-img" style="background-image: url('.$userdata['icon'].');">
					    '.$value[3].'
					</div>
					<div class="item-info">
						<p class="item-uname">'.$userdata['name'].'</p>
						<p class="item-desc">'.number_format($value[1],0,","," ").' LC</p>
					</div>
				</a>
			';
		}
		$generated = $table;
		
		return $generated;
	}

	echo users_list($cfg,$_GET['uids']);
?>