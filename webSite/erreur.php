<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>ChromeExtension</title>
</head>

<?php include './layout/includes.php'; ?>
<body>
	<?php include './layout/header.php'; ?>

	<div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
		<div class="w3-content w3-center">

			<h1>Oups an error has occured !</h1>

			<div class="w3-row-padding w3-row-padding w3-light-grey w3-margin w3-padding-16 w3-centered">
				<div class="w3-padding-16">
					<div class="w3-container w3-red">
						<h3><?php
						switch($_GET['erreur'])
						{
							case '400':
							echo 'Error 400 : HTTP scan failed.';
							break;
							case '401':
							echo 'Error 401 : The username or password is not correct!';
							break;
							case '402':
							echo 'Error 402 : The customer must reformulate his request with the correct data.';
							break;
							case '403':
							echo 'Error 403 : Request forbidden!';
							break;
							case '404':
							echo 'Error 404 : The page does not exist or never existed!';
							break;
							case '405':
							echo 'Error 405 : Unauthorized method.';
							break;
							case '500':
							echo 'Error 500 : Internal error in server or server full.';
							break;
							case '501':
							echo 'Error 501 : The server does not support the requested service.';
							break;
							case '502':
							echo 'Error 502 : Bad gateway.';
							break;
							case '503':
							echo 'Error 503 : Service unavailable.';
							break;
							case '504':
							echo 'Error 504 : Too much time to answer.';
							break;
							case '505':
							echo 'Error 505 : HTTP version not supported';
							break;
							default:
							echo 'Erreur !';
						}
						?></h3>

					</div>      
				</div>
			</div>
		</div>
	</div>

	<?php include './layout/footer.php'; ?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="./js/md5.min.js" type="text/javascript"></script>
	<script src="./js/register.js" type="text/javascript"></script>
</body>
</html>