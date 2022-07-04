<?php
	$base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
	$license = explode(",",file_get_contents('https://vklicense.do.am/license.txt'));
	foreach($license as $key => $value) {
		if ($value == $_SERVER['HTTP_HOST']) {
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
						<div style="text-align: center; position: absolute; top: 0; left: 0; min-height: 100vh; min-width: 100vw; background-color: #CEDCE2; z-index: 1000;">
							<p style="font-size: 180%; margin-top: 50vh; color: #08526F;">Приложение не лицензировано разработчиком</p>
						</div>
					</body>
				</html>
			';
			exit();
		}
	}
?>