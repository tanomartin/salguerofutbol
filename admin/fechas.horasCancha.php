<?
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";	
	include_once "../model/fechas.php";	
	include_once "../model/categorias.php";	
	include_once "../model/horas_cancha.php";
	include_once "include/config.inc.php";
	
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$operacion = "Carga Horas de Cancha Disponibles";
	$oFecha= new Fechas();
	$datos = $oFecha->get($_POST["id"]);
//	$datos = decodeUTF8($datos);	
	
	$horasCargadas = $oFecha->getHorasCancha($_POST["id"]);
	if ($horasCargadas != NULL) {
		$operacion = "Modificacion Horas de Cancha Disponibles";
	}
	
	$disabled = "";
	
	if( $_POST['accion'] == 'ver')
		$disabled = "disabled";

	$oTorneo= new Torneos();
	$aTorneos = $oTorneo->getByTorneoCat($datos[0]["idTorneoCat"]);

	$oHoras = new HorasCancha();
	$horas = $oHoras->getHorasDisponibles();

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
<script language="javascript">
	
	function volver(){
		document.form_alta.accion.value = "volver";
		document.form_alta.submit();
	}


	function validar() {
		$('#error').html("");
		var grupo = document.getElementById("form_alta").horas;
		var controlCheck = 0;
		for (i = 0; lcheck = grupo[i]; i++) {
			if (lcheck.checked) {
				controlCheck++;
			}
		}
		if (controlCheck < 4) {
			$('#error').html("* Debe seleccionar como mínimo 4 horas");
			return false;
		} else {
			document.form_alta.accion.value = "guardarHorasCancha";
			document.form_alta.submit();
		}
	}
	
	function seleccionarTodo() {
		var selectall = document.getElementById("form_alta").selectall;
		var checkall = false;
		if (selectall.checked) {
			checkall = true;
		} 
		var grupo = document.getElementById("form_alta").horas;
		for (i = 0; lcheck = grupo[i]; i++) {
			lcheck.checked = checkall;
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




<div class="mod_article block" id="register">

<div class="ce_text block">
	<h1><?=$operacion?> de Fechas</h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration g8 tableform block">

<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post"  enctype="multipart/form-data"> 


<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
<input type="hidden" name="accion" value="guardar" />

<!-- Filtros -->
<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
<!-- Fin filtros -->


<!-- Parametros menu -->
<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
<!--     -->

<div class="formbody">

	<div class="ce_table">
	
	<fieldset>
	<legend>Horas de Canchas Disponibles</legend>
	<legend><?=$datos[0]["nombre"]." - ".$datos[0]["torneo"]. " - ".$datos[0]["categoria"] ?></legend>
		<p><div id="error" style="color:#CC3300; font-weight:bold"></div></p>
		<p><div style="width:490px">
		<?  
			$i = 1;
			if ($horasCargadas != NULL) {
				foreach ($horas as $hora) {
					$checked = 0;
					foreach ($horasCargadas as $hc) {
						if ($hora['id'] == $hc['id_horas_cancha'] && $checked == 0) {
							print("<input type='checkbox' id='horas' name='hora".$hora["id"]."' value='".$hora["id"]."' checked> ".$hora["descripcion"]." | </input>"); 
							$checked = 1;
							$resto = $i % 4;
							if ($resto == 0) {
								print("<br>");
							}
							$i++;
						}
					}
					if ($checked == 0) {
						print("<input type='checkbox' id='horas' name='hora".$hora["id"]."' value='".$hora["id"]."'> ".$hora["descripcion"]." | </input>"); 
						$resto = $i % 4;
						if ($resto == 0) {
							print("<br>");
						}
						$i++;
					}
				}
			} else {
				foreach ($horas as $hora) {
					print("<input type='checkbox' id='horas' name='hora".$hora["id"]."' value='".$hora["id"]."'> ".$hora["descripcion"]." | </input>"); 
					$resto = $i % 4;
					if ($resto == 0) {
						print("<br>");
					}
					$i++;
				}
			} 
		?>
		<br><b>Seleccionar todo: </b><input type="checkbox" id="selectall" name="selectall" onclick="seleccionarTodo()" />
		</div>
		</p>
	</fieldset>

    <div class="submit_container">
	   <? if ( $disabled  == "" ) { ?>
		 <input class="submit" onclick="validar()" type="button" value="Guardar" /> 
		<? } ?>
		 <input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
		
    </div>
    </div>
</div>
</form>

</div>
<!-- indexer::continue -->


<div class="ce_text g4 xpln block">
	<p><strong>Carga de Horas disponibles</strong></p>
	<p>Debe Elegir como mínimo 4 horas para la fecha</p>
</div>
<div class="clear"></div>
</div>
</div>
	<div id="clear"></div>
</div>
</div>
<? include("pie.php")?>
</body>

</html>