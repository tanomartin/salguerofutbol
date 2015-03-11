<?	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/sedes.php";	
	include_once "../model/fixture.php";
	include_once "../model/categorias.php";
	
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$menu = "Secciones";

	$fechaPartidos = $_POST['fechaPartido'];
	$id_sede = $_POST['idSede'];
	$fechaPartidosSql = cambiaf_a_mysql($fechaPartidos);
	
	switch ($_POST["accion"]) {
		case "migrar":
			include("listadopartidos.migracion.php");
			break;
	}
	
	$oSede= new Sedes();
	$aSedes = $oSede->get();
	$oCetegoria = new Categorias();
	
	$oFixture = new Fixture();
	$listadoPartidos = 	$oFixture->getByFechaPartidoSede($fechaPartidosSql,$id_sede);
	for ($i=0; $i < sizeof($listadoPartidos); $i++) {
		$confEquipo1 = $oFixture->partidoConfirmado($listadoPartidos[$i]['idPartido'],$listadoPartidos[$i]['id1']);
		$confEquipo2 = $oFixture->partidoConfirmado($listadoPartidos[$i]['idPartido'],$listadoPartidos[$i]['id2']);
		$confirmacion = "";
		if ($confEquipo1 && $confEquipo2) {
			$confirmacion = "OK";
		} else {
			if ($confEquipo1) {
				$confirmacion = $listadoPartidos[$i]["equipo1"]; 
			}
			if ($confEquipo2) {
				$confirmacion = $listadoPartidos[$i]["equipo2"]; 
			}
		}
		$listadoPartidos[$i]['confirmacion'] = $confirmacion;
		if ($listadoPartidos[$i]['idzona'] != -1 && $listadoPartidos[$i]['idzona'] != 0) {
			$categoria = $oCetegoria->get($listadoPartidos[$i]['idzona']);
			$listadoPartidos[$i]['zona'] = "-".$categoria[0]['nombrePagina'];
		} else {
			$listadoPartidos[$i]['zona'] = "";
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

	function migrar(){
		document.frm_busqueda.accion.value = "migrar";		
		document.frm_busqueda.submit();
	}

	function limpiarAction() {
		document.frm_busqueda.accion.value = "";	
		document.frm_busqueda.submit();	
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

				<div class="mod_listing ce_table listing block" id="partnerlist">
                    <div >
                    <form name="frm_busqueda" id="frm_busqueda" action="<?=$_SERVER['PHP_SELF']?>" method="post">
						<div class="formbody">
							<input type="hidden" name="listadoPartidos" value="<?=urlencode(serialize($listadoPartidos)) ?>" />
							<input type="hidden" name="accion" value="" />
							Fecha:  <input name="fechaPartido" type="text" id="fechaPartido" size="11" value="<?=$_POST["fechaPartido"] ?>" readonly="readonly" />  <a href="javascript:show_calendar('document.frm_busqueda.fechaPartido', document.frm_busqueda.fechaPartido.value);"><img src="../_js/calendario2/cal.gif" width="16" height="16" border="0" /></a>  Sede: <select name="idSede" id='idSede' <?= $disabled ?> style="width:200px">
									<option value="-1">Seleccione una Sede...</option>
									<?php for($i=0;$i<count($aSedes);$i++) { ?>	
										<option value="<?php echo $aSedes[$i]['id'] ?>" <?php if ($_POST["idSede"] == $aSedes[$i]['id'] ) echo "selected"; ?>><?php echo $aSedes[$i]['nombre'] ?>
										</option>
									<?php } ?>	   
								  </select>
							<input class="submit" value="Listar" type="submit" style="font-size:11px" onclick="javascript:limpiarAction();"/>
                            <input class="submit" value="Limpiar" type="button" style="font-size:11px" onclick="javascript:limpiar('frm_busqueda'); javascript:limpiarAction();document.frm_busqueda.submit();" />
						</div>
                    </form>
                    </div>
					
                    <? if ($listadoPartidos != NULL) { 
					   	 $sede = $oSede->get($id_sede); ?>
						 <div class="ce_text block">
							<h1>Listado de Partidos del <?=$fechaPartidos?> - Sede <?=$sede[0]['nombre'] ?>
							  <div style="float:right"> 
									<img width="75" border="0" alt="reserva" title="Exportar Excel" onclick="javascript:migrar();" style="cursor:pointer" src="images/xls-icon.png"/>
							  </div>
							</h1>
						</div>
						<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
					  		<table width="900">
								<tr>
									<th>Hora</th>
									<th>Torneo</th>                                      
									<th>Equipo 1</th>                                                           
									<th>Equipo 2</th> 
									<th width="5%">Cancha</th> 
									<th>Juez</th>                                        
									<th>Conf.</th>  
								</tr>
							   
								<? foreach ($listadoPartidos as $partido) {?>
									<tr>
									 <td><?=$partido["horaPartido"]?></td>
									 <td><?=$partido["torneo"]."-".$partido["categoria"].$partido["zona"]?></td>
									 <td><?=$partido["equipo1"]?></td>
									 <td><?=$partido["equipo2"]?></td>
									 <td style="text-align:center"><?=$partido["cancha"]?></td>
									 <td></td>
									 <td style="text-align:center">
									 <? if ($partido["confirmacion"] == "OK") { ?>
									 		<img width="25" border="0" alt="reserva" title="Confirmado" src="../img/check.ico"/>
									 <?	} elseif ($partido["confirmacion"] == "") { ?>
									 		<img width="25" border="0" alt="reserva" title="Sin Confirmacion" src="../img/forbidden.ico"/>
									 <?	} else { ?> 
											<font color="#0000FF"><?=$partido["confirmacion"]?></font>
											<img width="25" border="0" alt="reserva" title="Sin Confirmacion" src="../img/blue-check-icon.png"/>
									 <?	} ?>
									 </td>
									</tr>
								<? }?>
							</table>
						</form>
					<? } elseif ($fechaPartidos != NULL && $id_sede != -1) {
							 	$sede = $oSede->get($id_sede);?>
								<h1>No hay de Partidos Cargado para la Fecha <?=$fechaPartidos?> - Sede <?=$sede[0]['nombre'] ?>
					<?  } ?>

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