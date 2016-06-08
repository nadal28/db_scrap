<?php
if(empty($_GET['ip'])){
	header('Location: ./');
	exit();
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.maxmind.com/geoip/v2.1/city/'.$_GET['ip'].'?use-downloadable-db=1&demo=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$response = json_decode(curl_exec($ch),true);
curl_close($ch);

?>

<html>
<head>
	<title><?php echo $_GET['ip']; ?></title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<meta charset="UTF-8">

	<style type="text/css">
		table {
			width: 100%;
			text-align: center;
		}
		th,td {
			background-color: #F5F5F5;
		}
	</style>
</head>
<body>
	<table>
		<tr>
			<th>IP</th>
			<th>Ubicación</th>
			<th>Código Postal</th>
			<th>ISP</th>
			<th>Organización</th>
			<th>Dominio</th>
		</tr>

		<tr>
			<td><?php echo $_GET['ip']; ?></td>
			<td>
				<?php
				echo $response['country']['names']['es'];
				echo '<br>';
				echo $response['subdivisions'][0]['names']['es'];
				echo '<br>';
				echo $response['subdivisions'][1]['names']['es'];
				?>
			</td>
			<td><?php echo $response['postal']['code']; ?></td>
			<td><?php echo $response['traits']['autonomous_system_organization']; ?></td>
			<td><?php echo $response['traits']['organization']; ?></td>
			<td><?php echo $response['traits']['domain']; ?></td>
		</tr>
	</table>
</body>
</html>
