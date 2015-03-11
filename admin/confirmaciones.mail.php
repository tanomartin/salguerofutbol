<?	include_once "include/config.inc.php";
	include_once "../model/fechas.php";
	include_once "../model/torneos.categorias.php";
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
	
	foreach ($equiposTorneo as $equipo) {
		$id = $equipo['id'];
		if ($partidos!= NULL){
			foreach ($partidos as $partido) {
				if ($id == $partido['idEquipo1'] || $id == $partido['idEquipo2']) {
					$equipoOb = new Equipos($id);
					$seEnvio = $equipoOb->seEnvioCorreo($id, $_POST['id'], 'c');
					$confirmado = $oFixture -> partidoConfirmado($partido['id'],$id);
					if (!$confirmado) {
						$equiposMail[$id] = array('id_equipo' => $id, 'nombre' => $equipoOb->nombre, 'email' => $equipoOb->email, 'seenvio' => $seEnvio);
					}
				}
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

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.blockUI.js"></script>
<script language="javascript">

function volver(){
	document.form_alta.accion.value = "confirmaciones";		
	document.form_alta.submit();
}

function eliminarEnvio(equipo){
	document.form_alta.accion.value = "eliminarEnvio";		
	document.form_alta.id_equipo.value = equipo;
	document.form_alta.submit();
}

function enviar() {
	var cuerpo = document.form_alta.cuerpocorreo.value.length;
	var asunto = document.form_alta.asuntocorreo.value;
	if (cuerpo == 0) {
		$('#errorcuerpo').html("* Debe ingresar un cuerpo del mensaje");
	}
	if (asunto == "") {
		$('#errorasunto').html("* Debe ingresar el asunto del mensaje");
	}
	if (cuerpo == 0 || asunto == "") {
		return false;
	} else {
		$.blockUI({ message: "<h1>Enviando Correos.<br> Aguarde por favor</h1>" });
		document.form_alta.accion.value = "enviarcorreo";		
		document.form_alta.submit();
	}
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
		  <div class="ce_text block">
				<h1>Correo Confirmacion: <?= $fecha[0]['nombre']." - ".$fecha[0]['torneo']." - ".$fecha[0]['categoria']?>
				  <div style="float:right"> 
				  	<? if ($equiposMail!= NULL) { ?><img width="75" border="0" alt="enviar" title="Enviar" onclick="javascript:enviar();" style="cursor:pointer" src="images/send_mail.png"/> <? }?>	
					<img width="75" border="0" alt="volver" title="Volver" onclick="javascript:volver();" style="cursor:pointer" src="images/back-icon.png"/>					
				  </div>
				</h1>
			</div>
			<div class="mod_article block" id="home">
				<div class="ce_text block">
					<div class="mod_listing ce_table listing block" id="partnerlist">
						<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post"  enctype="multipart/form-data"> 
							<input type="hidden" name="id" id="id"  value="<?=$_POST["id"]?>"/>
							<input type="hidden" name="id_equipo" id="id_equipo" />
							<input type="hidden" name="id_reserva" id="id_reserva" />
							<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
							<input type="hidden" name="accion" value="" />
							<!-- Filtros -->
							<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
							<!-- Fin filtros -->
							<!-- Parametros menu -->
							<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
							<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
							<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
							<input type="hidden" name="equiposMail" value="<?=urlencode(serialize($equiposMail)) ?>" />
							<!--     -->
						
						<div align="center" style="float:left">
						<table id="mail" width="450">
								<tr>
									<th><img width="15" border="0" alt="reserva" title="Con Reserva" src="../img/forbidden.ico"/> Equipos</th>
									<th>Email</th>
									<th width="8%">Enviado</th>
								</tr>
										<? if (sizeof($equiposMail) == 0) {
												print("<tr><td colspan='3'>No hay equipos</td></tr>");
											} else {
												foreach($equiposMail as $equipo) { ?>
												<tr>
													<td style="font-family:Geneva, Arial, Helvetica, sans-serif; font-size:17px"><?=$equipo['nombre'] ?></td>
													<td><?=$equipo['email'] ?></td>	
													<td nowrap>
														<? if ($equipo['seenvio']) { ?>
																<img border="0" src="../img/check.ico" id="info" alt="info" width="20px" height="20px" />
																<a href="javascript:eliminarEnvio(<?=$equipo['id_equipo']?>);"><img border="0" title="Reenviar" src="images/reenvio.png" id="info" alt="info" width="20px" height="20px" /></a>
														<? } else { ?>
																<img border="0" src="../img/forbidden.ico" id="info" alt="info" width="20px" height="20px" />
														<? } ?>
													</tr>	
										  <?	} 
											}?>
							</table>
						</div>
						<div align="center" style="float:right">
							<table id="cuerpo" width="450">
								<th>Correo</th>
								<tr><td>
									<div align="center" style="float:right">
										<div align="left">
											<h2>Asunto</h2>
											<p><div id="errorasunto" style="color:#CC3300; font-weight:bold" align="left"></div></p>
											<input type="text" id="asuntocorreo" name="asuntocorreo" size="63"/>
											<h2>Cuerpo</h2>
											<p><div id="errorcuerpo" style="color:#CC3300; font-weight:bold" align="left"></div></p>
											<textarea id="cuerpocorreo" name="cuerpocorreo" cols="50"></textarea>
										</div>
									</div>
								</td></tr>
							</table>
						</div>
						</form>
					</div>
				</div>
			</div> 
		</div>
	</div>
</div>
<? include("pie.php")?>
</body>

</html>