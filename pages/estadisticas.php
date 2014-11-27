<!DOCTYPE html>
<html lang="es">
<head>
	<?php 
		include "../pages/getProperty.php"; 
		include "../pages/conexion_bd.php";
	?>
	
	<meta charset="ISO-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="icon" href="../favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="../favicon.png" type="image/png"/>
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	<title><?php echo getPropiedad("titulo_head"); ?></title>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>
<body>
	<!--Menu-->
	<?php 
		$page="estadisticas"; 
		include "../pages/menu.php"; 
	?>

<div class="container">
	<h2>Estadísticas</h2>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">Apuestas con cotización inferior a <strong>@2</strong></div>
				<div class="panel-body">
					<strong>Total de apuestas: </strong>38<br>
					<strong>Acertadas: </strong>16<br>
					<strong>Porcentaje de acierto: </strong>42.11%<br>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">Apuestas con cotización mayor o igual a <strong>@2</strong> y menor de <strong>@3</strong></div>
				<div class="panel-body">
					<strong>Total de apuestas: </strong>41<br>
					<strong>Acertadas: </strong>18<br>
					<strong>Porcentaje de acierto: </strong>43.90%
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">Apuestas con cotización mayor o igual a <strong>@3</strong> y menor de <strong>@4</strong></div>
				<div class="panel-body">
					<strong>Total de apuestas: </strong>21<br>
					<strong>Acertadas: </strong>4<br>
					<strong>Porcentaje de acierto: </strong>19.04%
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">Apuestas con cotización mayor a <strong>@4</strong></div>
				<div class="panel-body">
					<strong>Total de apuestas: </strong>45<br>
					<strong>Acertadas: </strong>9<br>
					<strong>Porcentaje de acierto: </strong>20%
				</div>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">Riesgo por apostante</div>
				<div class="panel-body">
					<table class="table table-condensed table-hover table-bordered">
						<thead>
							<tr>
								<th>Apostante</th>
								<th>Nº de apuestas</th>
								<th>Nº de partidos apostados</th>
								<th>Nº de euros apostados</th>
								<th>Riesgo</th>
								<th>Indice Hez <span class="glyphicon glyphicon-sort-by-order"></span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Rulo</td>
								<td>25</td>
								<td>9</td>
								<td>31.5&euro;</td>
								<td>3.84</td>
								<td>560</td>
							</tr>
							<tr>
								<td>Zato</td>
								<td>19</td>
								<td>9</td>
								<td>31.5&euro;</td>
								<td>2.97</td>
								<td>389</td>
							</tr>
							<tr>
								<td>Matute</td>
								<td>13</td>
								<td>9</td>
								<td>31.5&euro;</td>
								<td>2.79</td>
								<td>382</td>
							</tr>							
							<tr>
								<td>Nano</td>
								<td>15</td>
								<td>9</td>
								<td>31.5&euro;</td>
								<td>2.48</td>
								<td>268</td>
							</tr>
							<tr>
								<td>Borja</td>
								<td>24</td>
								<td>10</td>
								<td>35&euro;</td>
								<td>3.20</td>
								<td>243</td>
							</tr>
							<tr>
								<td>Lucho</td>
								<td>26</td>
								<td>9</td>
								<td>31.5&euro;</td>
								<td>3.21</td>
								<td>183</td>
							</tr>
							<tr>
								<td>Tapia</td>
								<td>22</td>
								<td>10</td>
								<td>35&euro;</td>
								<td>2.73</td>
								<td>156</td>
							</tr>
							<tr>
								<td>Caballero</td>
								<td>22</td>
								<td>9</td>
								<td>31.5&euro;</td>
								<td>3.10</td>
								<td>112</td>
							</tr>
							
							
							
						</tbody>
					</table>
					<p><em>*El <strong>riesgo</strong> es la media de la cotización por euro apostado. </em></p>
					<p><em>*El <strong>&Iacute;ndice Hez</strong> es la suma del dinero ganado, de las apuestas acertadas, entre el total apostado, multiplicado por el riesgo</em></p>
				</div>
			</div>
		</div>	
	</div>
	<p class="text-right"><em>Datos actualizados el 26/11/2014 a las 14:38 </em></p>
 </div>


<!--
SELECT 
	apos.nombre, 
    SUM(a.apostado) as TOTAL_APOSTADO, 
    count(a.id) as NUM_APUESTAS,
    count(DISTINCT a.partido) as NUM_PARTIDOS,
        (SUM(a.cotizacion*a.apostado)/SUM(a.apostado))as RIESGO
FROM apuestas a
INNER JOIN partido_apost_apuesta paa ON paa.idapuesta=a.id
INNER JOIN partido_apostante pa ON paa.idpartidoapost=pa.id
LEFT JOIN apostantes apos ON apos.id=pa.idapostante
GROUP BY apos.nombre
ORDER BY RIESGO


SELECT 
	apos.nombre, 
    (SUM(a2.cotizacion*a2.apostado)/SUM(a.apostado))as HEZ
FROM partido_apost_apuesta paa
LEFT JOIN apuestas a ON paa.idapuesta=a.id 
LEFT JOIN apuestas a2 ON paa.idapuesta=a2.id and  a2.acertada=1
INNER JOIN partido_apostante pa ON paa.idpartidoapost=pa.id
LEFT JOIN apostantes apos ON apos.id=pa.idapostante

GROUP BY apos.nombre
ORDER BY HEZ
-->
<script src="../bootstrap/js/jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

