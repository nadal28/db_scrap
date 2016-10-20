<html>
<head>
	<title>DB Scrap</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<meta charset="UTF-8">

	<style type="text/css">
	html, body {
		margin: 10px 0;
		padding: 0;
	}
	#main {
		font-family: arial;
		max-width: 540px;
		text-align: center;
		margin: auto;
		min-height: 115px;
	}
	#titulo {
		text-decoration:underline;
		display:block;
		padding:20px 0;
		font-size:22px;
		font-weight:600;
	}
	.tabla {
		cursor:pointer;
		margin:10px;
		background: #2196F3;
		color: white;
		padding: 7px;
		font-weight: 600;
		border-radius: 5px;
	}
	table {
		width: 100%;
	}
	td {
		padding: 3px;
		text-align: center;
		font-size: 13px;
		font-weight: 600;
	}
	tr {
		margin: 20px;
	}

	tr:nth-child(odd) {
		background-color: #2196F3;
		color: white;
	}
	tr:nth-child(even) {
		background-color: #AEFFAD;
		color: #4C4C4C;
	}

	.globogif {
		height: 15px;
		float: right;
	}
	</style>
</head>
<body>
	<div id="main">
		<?php
		$conexion = mysqli_connect('host', 'user', 'pass', 'database');

		if(empty($_GET['tabla'])){
			$result = mysqli_query($conexion,'SHOW TABLES');

			if(mysqli_num_rows($result) != 0)
				echo '<span id="titulo">Tablas</span>';
			else
				exit('<span id="titulo">No hay tablas</span>');

			while($row = mysqli_fetch_assoc($result)){

				foreach($row as &$campo){
					echo '<span class="tabla" onclick="window.open(&#39;?tabla='.$campo.'&#39;,&#39;_self&#39;)">';
					echo $campo;
					echo '</span>';
				}
			}
		}else{
			$result = mysqli_query($conexion,'SELECT * FROM (SELECT * FROM '.mysqli_real_escape_string($conexion,$_GET['tabla']).' ORDER BY id DESC LIMIT 20) sub ORDER BY id ASC');
			if($result == false)
				exit('<META http-equiv="refresh" content="1;URL=./">La tabla '.$_GET['tabla'].' no existe');

			echo '<h3 style="cursor:pointer;" onclick="window.open(&#39;./&#39;,&#39;_self&#39;)">Volver</h3>';

			$cuenta_filas = mysqli_num_rows($result);
			if($cuenta_filas != 0)
				echo '<table>';
			else
				exit('<h2>No hay información</h2>');

			$campos = mysqli_fetch_fields($result);

			echo '<tr>';

			$count=0;
			foreach($campos as &$campo){	//Nombre de las columnas
				echo '<th>';
				echo $campo->name;
				echo '</th>';
				if(strtolower($campo->name) == 'ip')
					$posicion_columna_ip = $count;
				else
					++$count;
			}
			echo '</tr>';
			while($row = mysqli_fetch_assoc($result)){

				echo '<tr>';

				$count=0;
				foreach($row as &$valor){	//Valores
					echo '<td>';
					if(isset($posicion_columna_ip) && $count == $posicion_columna_ip){
						$valor = long2ip($valor);
						echo $valor;
						echo "\t";
						echo '<a href="geoip.php?ip=';
						echo $valor;
						echo '" target="_blank"><img class="globogif" src="globo.gif"></a>';
					}
					else
						echo $valor;
					echo '</td>';

					++$count;
				}
				echo '</tr>';
			}
			echo '</table>';
		}

		?>
	</div>
</body>
</html>
