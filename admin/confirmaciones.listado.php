<?	include_once "include/config.inc.php";
	include_once "../model/fechas.php";
	include_once "../model/torneos.categorias.php";
	include_once "../model/reservas.php";
	include_once "../model/fixture.php";
	include_once "../model/equipos.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	
	$menu = "Secciones";
	
	$oFecha = new Fechas();
	$fecha = $oFecha -> get($_POST['id']);
	
	$oFixture = new Fixture();
	$partidos = $oFixture -> getByFecha($_POST['id']);
	
	$oEquipo = new Equipos();
	$equiposTorneo = $oEquipo -> getTorneoCat($fecha[0]['idTorneoCat']);
	
	$oReservas = new Reservas();
	$reservasLibres = $oReservas -> getReservaLibresByIdFecha($_POST['id']);
	$reservas = $oReservas -> getReservaByIdFecha($_POST['id']);
	
	$i = 0;
	foreach ($equiposTorneo as $equipo) {
		$tienePartido = false;
		$tieneLibre = false;
		$id = $equipo['id'];
		if ($partidos!= NULL){
			foreach ($partidos as $partido) {
				if ($id == $partido['idEquipo1'] || $id == $partido['idEquipo2']) {
					$tienePartido = true;	
				}
			}
		}
		if ($reservas != NULL) {
			foreach ($reservas as $reserva) {
				if ($id == $reserva['id_equipo'] && $reserva['fecha_libre'] != 0) {
					$tieneLibre = true;
				}
			}
		}
		if (!$tienePartido && !$tieneLibre){
			$equiposSinDefinir[$i] = array('nombre' => $equipo['nombre']);
			$i++;
		}
	}
	
	$cruce = array();
	foreach ($equiposTorneo as $equipo1) {
		foreach ($equiposTorneo as $equipo2) {
			$jugaron = $oFixture -> jugaronEnContra($equipo1['id'], $equipo2['id'], $fecha[0]['idTorneoCat'], $_POST['id']);
			$juegaEstaFecha = $oFixture -> juegaEstaFecha($equipo1['id'], $equipo2['id'], $fecha[0]['idTorneoCat'], $_POST['id']);
			$id = $equipo1['id'].$equipo2['id'];			
			if ($jugaron) {
				$cruce[$id]['resultado']  = $oFixture -> resultadoPartido($equipo1['id'], $equipo2['id'], $fecha[0]['idTorneoCat'], $_POST['id']);
				$cruce[$id]['color'] = "#CCCCCC";
			}
			if ($juegaEstaFecha) {
				$cruce[$id]['color'] = "#0000CC";
			}
			if ($jugaron && $juegaEstaFecha) {
				$cruce[$id]['resultado'] = $oFixture -> resultadoPartido($equipo1['id'], $equipo2['id'], $fecha[0]['idTorneoCat'], $_POST['id']);
				$cruce[$id]['color'] = "#FF0000";
			}
			
			$jugaron = $oFixture -> jugaronEnContra($equipo2['id'], $equipo1['id'], $fecha[0]['idTorneoCat'], $_POST['id']);
			$juegaEstaFecha = $oFixture -> juegaEstaFecha($equipo2['id'], $equipo1['id'], $fecha[0]['idTorneoCat'], $_POST['id']);
			$id = $equipo2['id'].$equipo1['id'];
			if ($jugaron) {
				$cruce[$id]['resultado']  = $oFixture -> resultadoPartido($equipo2['id'], $equipo1['id'], $fecha[0]['idTorneoCat'], $_POST['id']);
				$cruce[$id]['color'] = "#CCCCCC";
			}
			if ($juegaEstaFecha) {
				$cruce[$id]['color'] = "#0000CC";
			}
			if ($jugaron && $juegaEstaFecha) {
				$cruce[$id]['resultado'] = $oFixture -> resultadoPartido($equipo2['id'], $equipo1['id'], $fecha[0]['idTorneoCat'], $_POST['id']);
				$cruce[$id]['color'] = "#FF0000";
			}
		}
	}
?>
    
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>

<!-- base href="http://www.typolight.org/" -->
<title>Panel de Control</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="Panel de Control.">
<meta name="keywords" content="">
<meta name="robots" content="index,follow">

<? include("encabezado.php"); ?>


<script language="javascript">

function volver(){
	document.form_alta.accion.value = "volver";		
	document.form_alta.submit();
}

function migrar(){
	document.form_alta.accion.value = "migrar";		
	document.form_alta.submit();
}

function mail(){
	document.form_alta.accion.value = "mail";		
	document.form_alta.submit();
}

function confirmacion(idpartido, idequipo, accion) {
	document.form_alta.accion.value = accion;
	document.form_alta.id_partido.value = idpartido;
	document.form_alta.id_equipo.value = idequipo;
	document.form_alta.submit();
}

</script>


</head>

<body id="top" class="home">

<div id="wrapper">

<!-- Header -->

<div id="header">
	<div class="inside">

<? include("top_menu.php"); ?>

<!-- indexer::stop -->
<!--
<div id="search">
<form action="search.html" method="get">
<div class="formbody">
  <label for="keywords" class="invisible">Search</label>
  <input name="keywords" id="keywords" class="text" type="text"><input src="index_archivos/search.png" alt="Search" value="Search" class="submit" type="image">
</div>
</form>
</div>
-->
<!-- indexer::continue -->

<!-- indexer::stop -->
<div id="logo">
	<a href="index.php" title="Volver al incio"><h1> Panel de Control</h1></a>
</div>
<!-- indexer::continue -->

<? include("menu.php");?>

 
	</div>

</div>

<!-- Header -->


<div id="container">
<div id="main">
    <div class="inside">
		<? include("path.php"); ?>
		<div class="mod_article block" id="home">
			<div class="ce_text block">
				<h1>Confirmacion de Partidos: <?= $fecha[0]['nombre']." - ".$fecha[0]['torneo']." - ".$fecha[0]['categoria']?>
				<div style="float:right"> 
					<img width="75" border="0" alt="reserva" title="Exportar Excel" onclick="javascript:migrar();" style="cursor:pointer" src="images/xls-icon.png"/>
				  	<? if ($partidos != NULL) { ?><img width="75" border="0" alt="reserva" title="Enviar Correo Recordatorio" onclick="javascript:mail();" style="cursor:pointer" src="images/eml-icon.png"/><? } ?>	
					<img width="75" border="0" alt="reserva" title="volver" onclick="javascript:volver();" style="cursor:pointer" src="images/back-icon.png"/>	
				</div>
				</h1>
				<br /><br />
				<div class="mod_listing ce_table listing block" id="partnerlist">
                    <form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post">
        				<input type="hidden" name="id" id="id"  value="<?=$_POST["id"]?>"/>
						<input type="hidden" name="id_equipo" id="id_equipo" />
						<input type="hidden" name="id_reserva" id="id_reserva" />
						<input type="hidden" name="id_partido" id="id_partido" />
						<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
						<input type="hidden" name="accion" value="" />
						<!-- Filtros -->
						<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
						<!-- Fin filtros -->
						<!-- Parametros menu -->
						<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
						<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
						<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
					</form>
					<div align="center" style="float:left">
						<table id="partidos" width="400">
								<tr>
									<th width="8%"></th>
									<th style="text-align:center">Partidos</th>
									<th width="8%"></th>
								</tr>
									<? if ($partidos != NULL) {
											foreach($partidos as $partido) { ?>
												<tr>
												<? if ($oFixture -> partidoConfirmado($partido['id'],$partido['idEquipo1'])) {?>
													<td nowrap>
														<a href="javascript:confirmacion('<?= $partido['id'] ?>','<?=$partido['idEquipo1']?>','eliminar');"> <img width="25" border="0" alt="reserva" title="Eliminar Confirmacion" src="images/reenvio.png"/></a>
														<img width="25" border="0" alt="reserva" title="Confirmado" src="../img/check.ico"/>	
													</td>
												<? } else {?>
													<td nowrap>
														<a href="javascript:confirmacion('<?= $partido['id'] ?>','<?=$partido['idEquipo1']?>','confirmar');"><img width="25" border="0" alt="reserva" title="Confirmar" src="images/icono-up.gif"/></a>
														<img width="25" border="0" alt="reserva" title="Sin Confirmacion" src="../img/forbidden.ico"/>
													</td>
												<? } ?>
													<td style="text-align:center; font-size:16px"><?=$partido['equipo1'] ?><font color="#FF0000"> VS </font> <?=$partido['equipo2']?> <br>(<?=$partido['horaPartido'] ?>)</td>
												<? if ($oFixture -> partidoConfirmado($partido['id'],$partido['idEquipo2'])) {?>
													<td nowrap>
														<img width="25" border="0" alt="reserva" title="Confirmado" src="../img/check.ico"/>
														<a href="javascript:confirmacion('<?= $partido['id'] ?>','<?=$partido['idEquipo2']?>','eliminar');"><img width="25" border="0" alt="reserva" title="Eliminar Confirmacion" src="images/reenvio.png"/></a>
													</td>
												<? } else {?>
													<td nowrap>
														<img width="25" border="0" alt="reserva" title="Sin Confirmacion" src="../img/forbidden.ico"/>
														<a href="javascript:confirmacion('<?= $partido['id'] ?>','<?=$partido['idEquipo2']?>','confirmar');"><img width="25" border="0" alt="reserva" title="Confirmar" src="images/icono-up.gif"/></a>
													</td>
												<? } ?>
												</tr>
									 <?  	}
									 	} else {
											print("<tr><td colspan='3' style='text-align:center'>No hay partidos para esta fecha </td></tr>");
										} ?>
								</table>
					</div>
					<div align="center" style="float:rigth">
						<table id="libres" width="400">
							<tr>
								<th colspan="2" style="text-align:center">Libres</th>
							</tr>
								
							<? if ($reservasLibres != NULL) { 
									foreach($reservasLibres as $reserva) {
										if ($reserva['fecha_libre'] != 0) {?>
											<tr><td style="font-size:16px"><?=$reserva['nombre'] ?></td>
										<? if ($reserva['fecha_libre'] == 1) { ?>
												<td>Fecha Libre Equipo</td>
										<? } else { ?>
												<td>Fecha Libre Gambeta</td>
											<? 	} ?>
											</tr>
										<?	}
										}
							 } else {
								print("<tr><td colspan='3' style='text-align:center'>No hay equipos con fecha libre para esta fecha </td></tr>");
							} ?>
						</table>
					</div>	
					<div align="center" style="float:center">
						<table id="libres" style="width:400px">
							<tr>
								<th style="text-align:center">Equipos sin definir</th>
							</tr>
						 <? if ($equiposSinDefinir != NULL) { 
								foreach($equiposSinDefinir as $equipos) { ?>
									<tr><td style="font-size:16px"><?=$equipos['nombre'] ?></td></tr>
							<?	}
							} else {
								print("<tr><td colspan='3' style='text-align:center'>No hay equipos sin definir para esta fecha </td></tr>");
							} ?>
						</table>
					</div>
				</div>	
				<div class="mod_listing ce_table listing block" id="partnerlist">
					<div align="center">
						<h1 align="left">Cruce de Equipos</h1>
						<table id="cruces" name="cruces" style="font-size:8.5px">
							  <tr><td style="background-color:#CE6C2B; color:#FFFFFF"><b>EQUIPOS</b></td>
						<? foreach ($equiposTorneo as $equipo) { ?>
							  <td style="background-color:#CE6C2B; color:#FFFFFF"><?=$equipo['nombre'] ?></td>
						<? } ?>
							</tr>
							<? foreach ($equiposTorneo as $equipo1) { ?>
							   <tr>
							   		<td style="background-color:#CE6C2B; color:#FFFFFF"><?=$equipo1['nombre'] ?></td>
									<? foreach ($equiposTorneo as $equipo2) { 
											if ($equipo1['id'] == $equipo2['id']) {  ?>
												<td style="background-color:#CE6C2B"></td>
										<? } else { 
												$id = $equipo1['id'].$equipo2['id']; 
												if (array_key_exists($id,$cruce)) { ?>
													<td style="background-color:<?=$cruce[$id]['color'] ?>;text-align:center;font-size:10px"><?= $cruce[$id]['resultado'][0]['golesEquipo1']."-".$cruce[$id]['resultado'][0]['golesEquipo2'] ?></td>
											<? } else { ?>
													<td></td>
											<? } ?>
										<? } ?>
										   
									<? } ?>
							   </tr>
						<? } ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="clear"></div>
</div>
</div>
<? include("pie.php")?>
</body>
</html>