<?	include_once "include/config.inc.php";
	include_once "../model/torneos.categorias.php";
	include_once "../model/correos.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	
	$menu = "Secciones";
	
	$oTorCat = new TorneoCat();
	$torneo = $oTorCat->getByIdCompleto($_POST['id_torneo_categoria']);
	
	$asunto = $_POST['asuntocorreo'];
	$cuerpo = $_POST['cuerpocorreo'];
	$equiposMail = unserialize(urldecode($_POST['equiposMail']));

	foreach($equiposMail as $equipo) {
		$idEquipo = $equipo['id_equipo'];
		$email = $equipo['email'];
		$nombre = $equipo['nombre'];
		if ($email != "" && !array_key_exists($idEquipo,$_POST)) {
			$valores = array('correo' => $email, 'cuerpo' => $cuerpo, 'equipoId' => $idEquipo, 'equipoNombre' => $nombre, 'asunto' => $asunto);
			$emailOb = new Correos($valores);
			$seEnvio = $emailOb->enviar();
			if ($seEnvio) {
				$correosEnviados[$idEquipo] = array('email' => $email, 'nombre' => $nombre, 'correcto' => 1);
			} else {
				$correosEnviados[$idEquipo] = array('email' => $email, 'nombre' => $nombre, 'correcto' => 0);
			}
		} else {
			$correosEnviados[$idEquipo] = array('email' => $email, 'nombre' => $nombre, 'correcto' => 0);
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
		document.frm_listado.accion.value = "volver";
		document.frm_listado.submit();		
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
				<h1>Resultado Envio: <?=$torneo->torneo." - ".$torneo->nombreLargo ?> <? if ($torneo->nombreCat != "" ) { echo " - ".$torneo->nombreCat;}?>
					<div style="float:right"> 
						<img width="75" border="0" alt="volver" title="volver" onclick="javascript:volver();" style="cursor:pointer" src="images/back-icon.png"/>	
					</div>
				</h1>
			</div>
			<div class="mod_article block" id="home">
				<div class="mod_listing ce_table listing block" id="partnerlist" align="center">
					<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
						<input type="hidden" name="id_torneo_categoria" value="<?=$_POST["id_torneo_categoria"]?>" />
						<input type="hidden" name="accion" value="" />
						<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
						<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
						<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
					</form>
						<div align="center" style="float:left">
						<table id="mail" width="450">
								<tr>
									<th>Equipos</th>
									<th>Email</th>
									<th width="8%"></th>
								</tr>
								<? if ($correosEnviados == NULL) {
										print("<tr><td colspan='3'>No hay equipos</td></tr>");
									} else {
										foreach($correosEnviados as $correos) { ?>
											<tr>
												<td style="font-family:Geneva, Arial, Helvetica, sans-serif; font-size:17px"><?=$correos['nombre'] ?></td>
												<td><?=$correos['email'] ?></td>	
													<? if ($correos['correcto'] == 1) { ?>
														<td><img width="17" border="0" alt="reserva" title="Con Reserva" src="../img/check.ico"/></td>
													<? } else { ?>
														<td><img width="17" border="0" alt="reserva" title="Con Reserva" src="../img/forbidden.ico"/></td>
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
									<h2>Asunto</h2>
									<div style="border-style:groove"><?=$_POST['asuntocorreo']?></div>
									<h2>Cuerpo</h2>
									<div style="border-style:groove"><?=$_POST['cuerpocorreo']?></div>
								</td></tr>
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