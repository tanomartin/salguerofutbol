<?
	include_once "../model/hoteles.php";	
	include_once "../model/secciones.php";	
	include_once "../model/seccionHotel.php";	
    include_once "../model/fckeditor.class.php" ;
	include_once "../model/provincias.php";
   //instancio editor


  $operacion = "Alta";
  $fecha = date("j/n/Y");

	if ($_POST["id"] != -1) {
	
		$operacion = "Modificaci&oacute;n";

		$oNovedad= new Novedades();
		$datos = $oNovedad->get($_POST["id"]);
		$fecha = $datos[0]['fecha'];
//		$datos = decodeUTF8($datos);	
	}

    $oFCKeditor = new FCKeditor( "texto" ) ;

    $oFCKeditor -> BasePath = '../_js/FCKeditor/' ;

	$oFCKeditor -> Height = 150 ;

	$oFCKeditor -> Width = 450 ;

	$oFCKeditor -> ToolbarSet = "custom2" ;

    $oFCKeditor -> InstanceName = "introduccion" ;

    $oFCKeditor -> Value = $datos[0]['introduccion'] ;

    $oFCKeditor1 = new FCKeditor( "texto" ) ;

    $oFCKeditor1 -> BasePath = '../_js/FCKeditor/' ;

	$oFCKeditor1 -> Height = 250 ;

	$oFCKeditor1 -> Width = 450 ;

	$oFCKeditor1 -> ToolbarSet = "custom2" ;

    $oFCKeditor1 -> InstanceName = "desarrollo" ;

    $oFCKeditor1 -> Value = $datos[0]['desarrollo'] ;

	  if ($_POST["id"] != -1)
		$id_novedad = $_POST["id"];
  	  else 	
		$id_novedad = time();

	$oObj1 = new Provincias();
    $datos1 = $oObj1->getByProvIdioma(1);

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

<title>Panel de Control</title>

<script language="javascript">

	function volver(){
	
		document.form_alta.accion.value = "novedades";		
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




<div class="mod_article block" id="register">

<div class="ce_text block">
	<h1><?=$operacion?> de Novedades </h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration g8 tableform block">

<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post">

			<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
			<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
			<input type="hidden" name="accion" value="guardar" />
			<input name="idtemporal" id="idtemporal"  value="<?=$id_novedad?>" type="hidden" />
            
   			<!-- Parametros menu -->
   				<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
	            <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
        	    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
            <!--     -->
	<div class="ce_table">
	
	<fieldset>
   <table width="108%" cellpadding="0" cellspacing="0" summary="Personal data">
  	<tbody>
          <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Fecha</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"> 
			 <input name="fecha" type="text" id="fecha" value="<?php echo $fecha;?>" size="8" readonly="readonly" />
			<a href="javascript:show_calendar('document.form_alta.fecha', document.form_alta.fecha.value);">
					<img src="../_js/calendario2/cal.gif" width="16" height="16" border="0" />
             </a>       
		</td>
      </tr>
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">T&iacute;tulo</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"> 
			 <input type="text" name="titulo" value="<?= $datos[0]['titulo'] ?>"  size="50"/>
		</td>
      </tr>  
          <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Lugar a Publicar</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"> 
			 <select name="posicion" id="posicion">
             <option value="-1" <? if ( $datos[0]['posicion'] == -1) echo 'selected="selected"' ?>>Home</option>
             <? for($i=0;$i<count($datos1); $i++) { ?>
    	         <option value="<?= $datos1[$i]['id']?>" <? if ( $datos[0]['posicion'] == $datos1[$i]['id']) echo 'selected="selected"' ?>><?= $datos1[$i]['nombre']?></option>
	         <? } ?>
           </select>
		</td>
      </tr>          
	</tbody>
	</table>
	</fieldset>

    <div class="submit_container">
   
    <input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Guardar" /> 
    <input class="submit" type="button" value="Limpiar" onclick="javascript:limpiar('form_alta');" />
    <input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
    
    
    
    </div>
    </div>
</form>

</div>
<!-- indexer::continue -->


<div class="ce_text g4 xpln block">

	<p><strong>Novedades</strong><br>
	Ingrese las novedades.</p>
	<p>Los campos marcados con <span class="mandatory">*</span> son de ingreso obligatorio.</p>

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
