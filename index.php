<?php
	include("cfg.php");
	
	if (!$_GET['viewer_id']) {
		$_GET['viewer_id'] = $_GET['vk_user_id'];
	}

	if ($_GET['vk_platform'] != 'mobile_android' && $_GET['vk_platform'] != 'mobile_iphone' && !in_array($_GET['viewer_id'], $cfg['admins'])) {
		echo '<div style="background-color: white; min-width: 100vw; min-height: 100vh; position: absolute; top: 0; left: 0; z-index: 10000; text-align: center;"><img src="dist/css/template.png?1" style="width: 100vw;"></div>';
		exit();
	}

	//check sign
	$base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
 	$url = $base_url . $_SERVER["REQUEST_URI"];
	$client_secret = $cfg['secret'];

	$query_params = []; 
	parse_str(parse_url($url, PHP_URL_QUERY), $query_params);
	$sign_params = []; 
	foreach ($query_params as $name => $value) { 
		if (strpos($name, 'vk_') !== 0) {
			continue;
		}
		$sign_params[$name] = $value;
	} 

	ksort($sign_params);
	$sign_params_query = http_build_query($sign_params);
	$sign = rtrim(strtr(base64_encode(hash_hmac('sha256', $sign_params_query, $client_secret, true)), '+/', '-_'), '=');
	include('dist/vkui-connect.js');
	$status = $sign === $query_params['sign'];
	if (!$status) {
		echo "Authentification Error";
		exit();
	}

	if ( $_GET['viewer_id'] && preg_match("/^[\d]+$/",$_GET['viewer_id']) ) {
		//check user
		$req = "SELECT * FROM `users` WHERE `id`='".$_GET['viewer_id']."'";
		$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));

		$hash = md5(rand(-200000000,200000000).$_GET['viewer_id']);

		if (!$data && $_GET['viewer_id']) {
			//new user get info
			$user_data = json_decode(file_get_contents("https://api.vk.com/method/users.get?fields=photo_50&user_ids=".$_GET['viewer_id']."&v=5.92&access_token=".$cfg['vktoken']),true)['response'][0];
			$other['icon'] = $user_data['photo_50'];
			$other['name'] = $user_data['first_name']." ".$user_data['last_name'];
			$other['shorturl'] = $user_data['domain'];
			$other = json_encode($other, JSON_UNESCAPED_UNICODE);

			$data['score'] = 0;
			$data['power'] = 0;
			$data['settings'] = '["default"]';

			//create new user
			$req = "INSERT INTO `users`(`id`,`other`,`hash`) VALUES ('".$_GET['viewer_id']."','".$other."','".$hash."')";
			mysqli_query($cfg['dbl'],$req);
		} else if ($data && $_GET['viewer_id'] && $data['id'] == $_GET['viewer_id'] && $data['hash'] != $hash) {
			$user_data = json_decode(file_get_contents("https://api.vk.com/method/users.get?fields=photo_50,domain&user_ids=".$_GET['viewer_id']."&v=5.92&access_token=".$cfg['vktoken']),true)['response'][0];
			$other['icon'] = $user_data['photo_50'];
			$other['name'] = $user_data['first_name']." ".$user_data['last_name'];
			$other['shorturl'] = $user_data['domain'];
			$other = json_encode($other, JSON_UNESCAPED_UNICODE);

			$req = "UPDATE `users` SET `other`='".$other."',`hash`='".$hash."' WHERE `id`='".$_GET['viewer_id']."'";
			mysqli_query($cfg['dbl'],$req);
		} else if (!$data && !$_GET['viewer_id']) {
			echo "Authentification Error";
			exit();
		}
	}
	$data['settings'] = json_decode($data['settings'],true);

	if (!in_array($_GET['viewer_id'], $cfg['admins']) && $cfg['service']) {
		echo '
			<!DOCTYPE html>
			<html>
				<head>
					<!-- JQuery JS -->
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
					<!-- VK Connect-->
					<script type="text/javascript" type="module" src="dist/vkconnect.js"></script>
					<script type="text/javascript">
						send("VKWebAppInit", {});
					</script>
				</head>
				<body>
					<div style="text-align: center; position: absolute; top: 0; left: 0; min-height: 100vh; min-width: 100vw; background-color: #CEDCE2; z-index: 1000; padding-top: 45%;">
						<img src="dist/css/loader.gif" style="margin-left: -28px; width: 50%;">
						<p style="font-size: 180%; color: #08526F;">Ведутся технические работы!</p>
					</div>
				</body>
			</html>
		';
		exit();
	}

	if ($data['role'] == 'ban') {
		echo '
			<!DOCTYPE html>
			<html>
				<head>
					<!-- JQuery JS -->
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
					<!-- VK Connect-->
					<script type="text/javascript" type="module" src="dist/vkconnect.js"></script>
					<script type="text/javascript">
						send("VKWebAppInit", {});
					</script>
				</head>
				<body>
					<div style="text-align: center; position: absolute; top: 0; left: 0; min-height: 100vh; min-width: 100vw; background-color: #CEDCE2; z-index: 1000; padding-top: 45%;">
						<img src="dist/css/lock.png" width: 20%;">
						<p style="font-size: 180%; color: #08526F;">Вы заблокированы</p>
					</div>
				</body>
			</html>
		';
		exit();
	}
	// foreach ($_GET as $key => $value) {
	// 	$getdata .= '['.$key.'] '.$value.', ';
	// }
	// w_log($getdata);
?>
                                    
		<script type="text/javascript" src="//vk.com/js/api/xd_connection.js?2" charset="utf-8"></script>
		<script type="text/javascript" src="//ad.mail.ru/static/admanhtml/rbadman-html5.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="//vk.com/js/api/adman_init.js" charset="utf-8"></script>
		<script>
			window.addEventListener('load', function() {
				var user_id = null;   // id пользователя
				var app_id = 7203972;  // id вашего приложения

				admanInit({
					user_id: <?=$_GET['vk_user_id']?>,
					app_id: 7203972,
					mobile: true,
					type: 'preloader'
				}, onAdsReady, onNoAds);

				function onAdsReady(adman) {
					adman.onStarted(function () {});
					adman.onCompleted(function() {});
					adman.onSkipped(function() {});      
					adman.onClicked(function() {}); 
					adman.start('preroll');
				};
				function onNoAds() {};
			});
		</script>
       
<!DOCTYPE html>
<html>
	<head>
		<title>Love Coin</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover">
		<meta name="theme-color" content="#ffffff">
		<meta content="IE=Edge" http-equiv="X-UA-Compatible">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<!-- Custom CSS -->
		<link rel="stylesheet" type="text/css" href="dist/css/main.css?r=<?=rand(0,999999999)?>">

		<!-- JQuery JS -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!-- VK Connect-->
		<script type="text/javascript" type="module" src="dist/vkconnect.js"></script>
		<script src="dist/scroll-lock.min.js"></script>
		<script type="text/javascript">
			scrollLock.disablePageScroll();
		</script>
	</head>
	<body>
		<div class="container-fluid screen" id="main">
			<div class="content">
				<div class="col-12" id="coin-counter">
					<p class="title">Баланс</p>
					<p class="title">L o v e  C o i n</p>
					<p id="totalcounter"><?=$data['score']?></p>
				</div>
				<div class="col-12" id="power-counter">
					<p class="title">Автомайнинг</p>
					<p id="powercounter">+<?=$data['power']?> LC в/сек</p>
				</div>
				<div id="main-menu-btns" class="row">
					<div class="col-<?=$cfg['menu-btns']?>">
						<img class="menubtn" id="mainmenubtn-top" src="skins/default/menu/btn1.png">
					</div>
					<div class="col-<?=$cfg['menu-btns']?>">
						<img class="menubtn" id="mainmenubtn-transfer" src="skins/default/menu/btn2.png">
					</div>
					<div class="col-<?=$cfg['menu-btns']?>">
						<img class="menubtn" id="mainmenubtn-market" src="skins/default/menu/btn3.png">
					</div>
					<!--<div class="col-<?=$cfg['menu-btns']?>">
						<img class="menubtn" id="mainmenubtn-games" src="skins/default/menu/btn4.png">
					</div -->
				</div>
				<div id="main-menu-title" class="row">
					<div class="col-<?=$cfg['menu-btns']?>">
						<p>Топ</p>
					</div>
					<div class="col-<?=$cfg['menu-btns']?>">
						<p>Перевод</p>
					</div>
					<div class="col-<?=$cfg['menu-btns']?>">
						<p>Магазин</p>
					</div>
					<!--<div class="col-<?=$cfg['menu-btns']?>">
						<p>Игры</p>
					</div -->
				</div>
				<div class="col-12" id="coin-block">
					<div id="coin" style="background-image: url(skins/default/bigBtn.png);"><div id="onlineCounter" class="text-center text-white pb-5 position-absolute" style="bottom:0; width: 90%;"></div>
<script type="text/javascript">
 var minOnline = 30;
 var online;
 var onlineTimer = 100;

 setInterval(function(){
  if (onlineTimer >= 100) {
   online = minOnline+Math.floor(Math.random()*8);
   $('#onlineCounter').text('Online: '+online);
   onlineTimer = 0;
  } else {
   onlineTimer++;
  }
 },100);
</script></div>
				</div>
			</div>
		</div>


		<script type="text/javascript">			
			var counter = 1;
			var power = <?=$data['power']?>;
			function fail_notify(){
				$("body").prepend('<div class="notify" style="position: absolute; margin-top: 33vh; z-index: 1000; width: 100%; text-align: center;"><div style="margin: 0 auto; width: 1.5em; height: 1.5em; font-size: 6em; color: #c9c9c9; background: white; padding: 0 0; border-radius: 10px; border: 1px solid gray;">✕</div></div>');
				$(".notify").animate({
						opacity: 0
					}, 900, "linear", function(){
						$(".notify").remove();
					}
				);
			}
			function success_notify(){
				$("body").prepend('<div class="notify" style="position: absolute; margin-top: 33vh; z-index: 1000; width: 100%; text-align: center;"><div style="margin: 0 auto; width: 1.5em; height: 1.5em; font-size: 6em; color: #c9c9c9; background: white; padding: 0 0; border-radius: 10px; border: 1px solid gray;">✓</div></div>');
				$(".notify").animate({
						opacity: 0
					}, 900, "linear", function(){
						$(".notify").remove();
					}
				);
			}

			$('#coin').click(function(){
				$("#totalcounter").text( +$("#totalcounter").text() + 1 );
				counter += 1;

				$('#coin').animate({
						"background-size": "95%",
					}, 50, "linear", function() {
						$('#coin').animate({
							"background-size": "100%",
						}, 50, "linear");
					}
				);
			});

			$('.menubtn').click(function(){
				var uri = 'overlay.php?hash=<?=$hash?>&uid=<?=$_GET['viewer_id']?>&device=<?=$_GET['vk_platform']?>&type='+$(this).attr('id');
				$.ajax({
					url: uri,
					cache: false,
					success: function(html){
						if (html != 'fail') {
						    $('.content').append(html);
							$('.overlay').animate({
									top: "0"
								}, 200, "linear"
							);
						} else {
							fail_notify();
						}
					}
				});
			});

			$(document).ready(function(){
				send('VKWebAppInit', {});
				send("VKWebAppSetViewSettings", {"status_bar_style": "light", "action_bar_color": "#000"});
				send("VKWebAppGetAuthToken", {"app_id": 6985126, "scope": "friends,wall"});
				subscribe((e) => {
					if ( e.detail.type === "VKWebAppAccessTokenReceived" ) {
						var uri = "tokenupd.php?token="+e.detail.data.access_token+"&uid=<?=$_GET['viewer_id']?>&hash=<?=$hash?>";
						$.ajax({
							url: uri,
							cache: false,
							success: function(html){
								
							}
						});
					}
				});

				setInterval(function(){
					var uri = 'logic.php?hash=<?=$hash?>&gid=<?=$_GET['vk_group_id']?>&uid=<?=$_GET['viewer_id']?>&automine=true&score='+counter;
					if ( $("#totalcounter").text() != '+0 LC в/сек' ) {
						$.ajax({
							url: uri,
							cache: false,
							success: function(html){
								if (html != 'fail') {
									$("#totalcounter").text( html.split(",")[0] );
									$("#powercounter").text( "+" + html.split(",")[1] + " LC в/сек" );
									power = +html.split(",")[1];
									counter = 1;
									console.log(html);
								} else {
									fail_notify();
									$("body").children().remove();
									$("body").html('<div style="position: absolute; margin-top: 50vh; z-index: 1000; width: 100%; text-align: center;"><div style="margin: 0 auto; font-size: 1em; color: black;">Обнаружен вход с другого устройства</div></div>');
								}
							}
						});
					}
				},1e4);

				setInterval(function(){
					$("#totalcounter").text( +$("#totalcounter").text() + power );
				},1e3);
			});
		</script>
	</body>
</html>