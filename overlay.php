<?php
	include("cfg.php");

	$data = authcheck($cfg,$_GET['uid'],$_GET['hash']);

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

	function act_list($cfg,$uid) {
		$req = "SELECT * FROM `users` WHERE `id`='".$uid."'";
		$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));
		$req = "SELECT * FROM `act` WHERE `enabled`>'0'";
		$actdata = mysqli_fetch_all(mysqli_query($cfg['dbl'],$req));

		foreach ($actdata as $key => $value) {
			if ( !stristr($value[2], $uid) ) {
				$value[1] = json_decode($value[1],true);
				if ($value[1]['type'] == 'sub') {
					$generated .= '
						<div class="list-item row" id="sub-box-'.$value[0].'">
							<img class="item-img" src="'.$value[1]['icon'].'">
							<div class="item-info">
								<p class="item-uname">'.$value[1]['name'].'</p>
								<p class="item-desc">'.$value[1]['desc'].'</p>
							</div>
							<script>
								var clicked'.$value[0].' = 0;
								$("#sub-box-'.$value[0].'").click(function(){
									if ( clicked'.$value[0].' == 0 ) {
										clicked'.$value[0].' = 1;
										send("VKWebAppJoinGroup", {"group_id": '.$value[1]['content'].'});
									}
								});
								subscribe((e) => {
									if ( e.detail.type === "VKWebAppJoinGroupResult" && clicked'.$value[0].' == 1 ) { 
										clicked'.$value[0].' = 2;
										var uri = "rewardcheck.php?type=sub&uid='.$uid.'&hash='.$_GET['hash'].'&id='.$value[0].'";
										$.ajax({
											url: uri,
											cache: false,
											success: function(html){
												$("#sub-box-'.$value[0].'").remove();
											}
										});
									}
								});
							</script>
						</div>
					';
				} else if ($value[1]['type'] == 'share') {
					$generated .= '
						<div class="list-item row" id="share-box-'.$value[0].'">
							<img class="item-img-quad" src="dist/css/share.png">
							<div class="item-info">
								<p class="item-uname">'.$value[1]['name'].'</p> 
								<p class="item-desc">'.$value[1]['desc'].'</p>
							</div>
							<script>
								var clicked'.$value[0].' = 0;
								$("#share-box-'.$value[0].'").click(function(){
									if ( clicked'.$value[0].' == 0 ) {
										clicked'.$value[0].' = 1;
										send("VKWebAppShowWallPostBox", {'.$value[1]['content'].'});
									}
								});
								subscribe((e) => {
									if ( e.detail.type === "VKWebAppShowWallPostBoxResult" && clicked'.$value[0].' == 1 ) { 
										clicked'.$value[0].' = 2;
										var uri = "rewardcheck.php?type=share&uid='.$uid.'&id='.$value[0].'&hash='.$_GET['hash'].'&post-id="+e.detail.data.post_id;
										$.ajax({
											url: uri,
											cache: false,
											success: function(html){
												$("#share-box-'.$value[0].'").remove();
											}
										});
									}
								});
							</script>
						</div>
					';
				} else if ($value[1]['type'] == 'link') {
					$generated .= '
						<a class="list-item row" target="_blank" href="'.$value[1]['content'].'">
							<img class="item-img-quad" src="'.$value[1]['icon'].'">
							<div class="item-info">
								<p class="item-uname">'.$value[1]['name'].'</p> 
								<p class="item-desc">'.$value[1]['desc'].'</p>
							</div>
						</a>
					';
				}
			}
		}
		$generated = '
			<div class="list-menu row">
				<p class="col-6" id="listmenubtn-market">Улучшения</p>
				<p class="col-6 active">Акции</p>
			</div>
		'.$buyRole.$generated;
		return $generated;
	}

	function groups_list($cfg,$uid) {
		$req = "SELECT * FROM `groups` ORDER BY `score` DESC LIMIT 100";
		$data = mysqli_fetch_all(mysqli_query($cfg['dbl'],$req));
		foreach ($data as $key => $value) {
			$gids[] = $value[0];
			$scores[] = $value[1];
		}
		$data = json_decode(file_get_contents("https://api.vk.com/method/groups.getById?group_ids=".implode(",",$gids)."&v=5.92&access_token=".$cfg['vktoken']),true)['response'];
		foreach ($data as $key => $value) {
			$table .= '
				<a href="https://vk.com/club'.$value['id'].'" target="_blank" class="list-item row">
					<p class="item-count">'.list_counter($key).'</p>
					<img class="item-img" src="'.$value['photo_50'].'">
					<div class="item-info">
						<p class="item-uname">'.$value['name'].'</p>
						<p class="item-desc">'.number_format($scores[$key],0,","," ").' LC</p>
					</div>
				</a>
			';
		}
		$generated = '
			<div class="list-menu row">
				<p class="col-4" id="listmenubtn-user-top">Общий</p>
				<p class="col-4" id="listmenubtn-friends-top">Друзья</p>
				<p class="col-4 active">Группы</p>
			</div>
		'.$table;
		return $generated;
	}

	function users_list($cfg,$uid) {
		$req = "SELECT * FROM `users` ORDER BY `score` DESC LIMIT 100";
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
		$generated = '
			<div class="list-menu row">
				<p class="col-4 active">Общий</p>
				<p class="col-4" id="listmenubtn-friends-top">Друзья</p>
				<p class="col-4" id="listmenubtn-group-top">Группы</p>
			</div>
		'.$table;
		
		return $generated;
	}

	function friends_list($cfg,$uid) {
		$generated = '
			<div class="list-menu row">
				<p class="col-4" id="listmenubtn-user-top">Общий</p>
				<p class="col-4 active">Друзья</p>
				<p class="col-4" id="listmenubtn-group-top">Группы</p>
				<script>
					send("VKWebAppGetAuthToken", {"app_id": '.$cfg['appid'].', "scope": "friends"});
					subscribe((e) => {
						if ( e.detail.type === "VKWebAppAccessTokenReceived" ) {
							send("VKWebAppCallAPIMethod", {"method": "friends.getAppUsers", "params": {"v":"5.95", "access_token":e.detail.data.access_token}});
							subscribe((e) => {
								if ( e.detail.type === "VKWebAppCallAPIMethodResult" ) {
									var uri = "friends.php?uid='.$_GET['uid'].'&hash='.$_GET['hash'].'&uids="+e.detail.data.response.join(",");
									$.ajax({
										url: uri,
										cache: false,
										success: function(html){
											$(".list").children().not(".list-menu").remove();
											$(".list").append(html);
										}
									});
								}
							});
						}
					});
				</script>
			</div>
		';
		return $generated;
	}

	function goods_list($cfg,$uid) {
		$req = "SELECT * FROM `users` WHERE `id`='".$uid."'";
		$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));
		$req = "SELECT * FROM `goods`";
		$goods = mysqli_fetch_all(mysqli_query($cfg['dbl'],$req));

		foreach ($goods as $key => $value) {
			if ( $value[3] == 1 || in_array($uid, $cfg['admins']) ) {
				$value[1] = json_decode($value[1],true);

				$newprice = 1.5 * $value[1]['price'] * $data[$value[2]];
				if ($newprice > 0) { $value[1]['price'] = $newprice; }

				$value[1]['price'] = strrev(implode('K',explode("000",strrev($value[1]['price']))));
				$generated .= '
					<div class="list-item row">
						<img class="item-img-quad" src="'.$value[1]['img'].'">
						<div class="item-info">
							<p class="item-uname">'.$value[1]['name'].'</p>
							<div id="'.$value[2].'" class="item-action-btn">Купить за '.$value[1]['price'].' <p>LC</p></div>
						</div>
						<p class="item-desc">'.$value[1]['title'].'</p>
					</div>
				';
			}
		}
		$generated = '
			<div class="list-menu row">
				<p class="col-6 active">Улучшения</p>
				<p class="col-6" id="listmenubtn-act">Акции</p>
			</div>
			<a href="https://vk.com/coin#x'.$cfg['vc_api_uid'].'_1000_'.rand(-2000000000,2000000000).'_1" target="_blank" class="list-item row">
				<img class="item-img" src="dist/css/vkcoin.png">
				<div class="item-info">
					<p class="item-uname">VK Coin в LC</p>
					<p class="item-desc">'.$cfg['vc_exchange_rate'].' VK Coin = 1 LC</p>
				</div>
			</a>
		'.$generated.'
			<script>
				$(".item-action-btn").click(function(){
					var uri = "logic.php?hash='.$_GET['hash'].'&uid='.$_GET['uid'].'&type=action-btn&act-type="+$(this).attr("id");
					$.ajax({
						url: uri,
						cache: false,
						success: function(html){
							if (html != "fail") {
								$("#totalcounter").text( html.split(",")[0] );
								$("#powercounter").text( "+" + html.split(",")[1] + " LC/сек" );
								var uri = "overlay.php?hash='.$_GET['hash'].'&uid='.$_GET['uid'].'&device='.$_GET['device'].'&type=listmenubtn-market";
								$.ajax({
									url: uri,
									cache: false,
									success: function(html){
										if (html != "fail") {
											$(".list").children().remove();
											$(".list").html(html);
										}
									}
								});
								success_notify();
							} else {
								fail_notify();
							}
						}
					});
				});
			</script>
		';
		return $generated;
	}

	function transfer_list($cfg,$uid) {
		$generated = '
			<div class="list-menu row">
				<p class="col-6 active">Перевод</p>
				<p class="col-6" id="listmenubtn-transactions">История</p>
			</div>
			<div class="list-item row" id="protected">
				<div class="item-input-box">
					<input type="number" placeholder="Сумма перевода" class="item-score-input">
				</div>
			</div>
			<div class="list-item row" id="protected">
				<div class="item-input-box">
					<input type="text" placeholder="Поиск" class="item-text-input">
				</div>
			</div>
			<script>
				$(".item-text-input").keyup(function(event){
					let uri = "transfer.php?hash='.$_GET['hash'].'&uid='.$_GET['uid'].'&find="+$(this).val();
					$.ajax({
						url: uri,
						cache: false,
						success: function(html){
							$(".list-item").not("#protected").remove();
							$(".list-item").parent().append(html);
						}
					});
				});
			</script>
		';
		return $generated;
	}

	function transactions_list($cfg,$uid) {
		$req = "SELECT * FROM `transactions` WHERE `toid`='".$uid."' OR `fromid`='".$uid."' ORDER BY `date` DESC LIMIT 25";
		$data = mysqli_fetch_all(mysqli_query($cfg['dbl'],$req));
		if ($data) {
			foreach ($data as $key => $value) {
				$uids[] = $value[3] == $uid ? $value[2] : $value[3];
				$tdata[] = $value;
			}
			$data = json_decode(file_get_contents("https://api.vk.com/method/users.get?fields=photo_50&user_ids=".implode(",",$uids)."&v=5.92&access_token=".$cfg['vktoken']),true)['response'];
			foreach ($uids as $key => $value) {
				if ($value != 1) {
					foreach ($data as $val) {
						if ($value == $val['id']) {
							$uname = $val['first_name']." ".$val['last_name'];
							$icon = $val['photo_50'];
							$id = $val['id'];
							break;
						}
					}
				} else {
					$uname = 'VK Coin';
					$icon = 'dist/css/vkcoin.png';
					$id = 0;
				}
	
				
				if ($tdata[$key][3] != $uid) {
					$score = 'style="color: red; font-weight: bolder;">- '.number_format($tdata[$key][4],0,","," ").' LC';
				} else {
					$score = 'style="color: green; font-weight: bolder;">+ '.number_format($tdata[$key][4],0,","," ").' LC';
				}
				
				$table .= '
					<a href="https://vk.com/id'.$id.'" target="_blank" class="list-item row">
						<img class="item-img" src="'.$icon.'">
						<div class="item-info">
							<p class="item-uname">'.$uname.'</p>
							<p class="item-desc" '.$score.'</p>
						</div>
						<p class="item-desc" style="color: gray; margin-top: 4em;">'.date('H:i:s d.m.Y',$tdata[$key][1]).'</p>
					</a>
				';
			}
		} else {
			$table = '<p>Тут пока ничего нет</p>';
		}
		$generated = '
			<div class="list-menu row">
				<p class="col-6" id="listmenubtn-transfer">Перевод</p>
				<p class="col-6 active">История</p>
			</div>
		'.$table;
		return $generated;
	}
	
	function games_list($cfg,$uid) {
		$req = "SELECT * FROM `users` WHERE `id`='".$uid."'";
		$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));

		$glist = scandir("games");
		foreach ($glist as $key => $value) {
			if ($key < 3) {
				continue;
			} else {
				$gamedata = json_decode(file_get_contents("games/".$value."/data.json"),true);
				$generated .= '
					<div class="list-item row">
						<img class="item-img-quad" src="games/'.$value.'/icon.png">
						<div class="item-info">
							<p class="item-uname">'.$gamedata['name'].'</p>
							<div id="'.$value.'" class="item-action-btn">Играть</div>
						</div>
					</div>
				';
			}
		}
		
		$generated = $generated.'
			<script>
				$(".item-action-btn").click(function(){
					var uri = "games/game.php?hash='.$_GET['hash'].'&uid='.$_GET['uid'].'&name="+$(this).attr("id");
					$.ajax({
						url: uri,
						cache: false,
						success: function(html){
							if (html != "fail") {
							    $(".content").append(html);
								$("#game_overlay").animate({
										top: "0"
									}, 200, "linear"
								);
							} else {
								fail_notify();
							}
						}
					});
				});

			</script>
		';
		return $generated;
	}

	if (stristr($_GET['type'], "mainmenubtn-")) {
		if ($_GET['type'] == 'mainmenubtn-top') {
			//top
			$type = "Топ";
			$table = users_list($cfg,$_GET['uid']);
		} else if ($_GET['type'] == 'mainmenubtn-transfer' && preg_match("/^[\d]+$/",$_GET['uid'])) {
			//transfer
			$type = "Переводы";
			$table = transfer_list($cfg,$_GET['uid']);
		} else if ($_GET['type'] == 'mainmenubtn-market' && preg_match("/^[\d]+$/",$_GET['uid'])) {
			//market
			$type = "Магазин";
			$table = goods_list($cfg,$_GET['uid']);
		} else if ($_GET['type'] == 'mainmenubtn-games' && preg_match("/^[\d]+$/",$_GET['uid'])) {
			//market
			$type = "Игры";
			$table = games_list($cfg,$_GET['uid']);
		} else {
			exit();
		}
	
		echo '
			<div class="overlay">
				<div class="overlay-bg">
					<div class="overlay-control-block">
						<p class="overlay-active">❮</p>
						<p>'.$type.'</p>
					</div>
					<div class="col-12 list" data-scroll-lock-scrollable>
						'.$table.'
					</div>
				</div>
				<script>
					$(".overlay-active").click(function(){
						$(".overlay").animate({
							top: "100vh"
						}, 60, "linear", function(){
							$(".overlay").remove();
						});
					});

					$(".list-menu").children().click(function(){
						if ($(this).attr("id") != undefined) {
							var uri = "overlay.php?hash='.$_GET['hash'].'&uid='.$_GET['uid'].'&device='.$_GET['device'].'&type="+$(this).attr("id");
							$.ajax({
								url: uri,
								cache: false,
								success: function(html){
									if (html != "fail") {
										$(".list").children().remove();
										$(".list").html(html);
									} else {
										fail_notify();
									}
								}
							});
						}
					});
				</script>
			</div>
		';
	}

	if (stristr($_GET['type'], "listmenubtn-")) {
		if ($_GET['type'] == 'listmenubtn-user-top') {
			//user top
			echo users_list($cfg,$_GET['uid']);
		} else if ($_GET['type'] == 'listmenubtn-transfer' && preg_match("/^[\d]+$/",$_GET['uid'])) {
			//transfer
			echo transfer_list($cfg,$_GET['uid']);
		} else if ($_GET['type'] == 'listmenubtn-transactions' && preg_match("/^[\d]+$/",$_GET['uid'])) {
			//transactions
			echo transactions_list($cfg,$_GET['uid']);
		} else if ($_GET['type'] == 'listmenubtn-market' && preg_match("/^[\d]+$/",$_GET['uid'])) {
			//goods
			echo goods_list($cfg,$_GET['uid']);
		} else if ($_GET['type'] == 'listmenubtn-act' && preg_match("/^[\d]+$/",$_GET['uid'])) {
			//themes
			echo act_list($cfg,$_GET['uid']);
		} else if ($_GET['type'] == 'listmenubtn-friends-top') {
			//group top
			echo friends_list($cfg,$_GET['uid']);
		} else if ($_GET['type'] == 'listmenubtn-group-top') {
			//group top
			echo groups_list($cfg,$_GET['uid']);
		} else {
			exit();
		}
	
		echo '
			<script>
				$(".list-menu").children().click(function(){
					//send("VKWebAppGetAuthToken", {"app_id": 6986919, "scope": "friends"});
					if ($(this).attr("id") != undefined) {
						var uri = "overlay.php?hash='.$_GET['hash'].'&uid='.$_GET['uid'].'&device='.$_GET['device'].'&type="+$(this).attr("id");
						$.ajax({
							url: uri,
							cache: false,
							success: function(html){
								if (html != "fail") {
									$(".list").children().remove();
									$(".list").html(html);
								} else {
									fail_notify();
								}
							}
						});
					}
				});
			</script>
		';
	}
?>