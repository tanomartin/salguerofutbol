<?	include_once "include/fechas.php";
	include_once "../model/torneos.php";
	
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$operacion = "Alta";

	$fechaInicio = date("j/n/Y");
	$fechaFin = date("j/n/Y");
	
	if ($_POST["id"] != -1) {
	
		$operacion = "Modificaci&oacute;n";

		$oTorneo= new Torneos();
		$datos = $oTorneo->get($_POST["id"]);

		$fechaInicio =	cambiaf_a_normal($datos[0]["fechaInicio"]);
		$fechaFin =	cambiaf_a_normal($datos[0]["fechaFin"]);		
//		$datos = decodeUTF8($datos);	
	}

	if( $_POST['accion'] == 'ver')
		$disabled = "disabled";

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
	<h1><?=$operacion?>
	   del Torneo</h1>
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
	<legend>Datos del Torneo

	</legend><table summary="Personal data" cellpadding="0" cellspacing="0">
  	<tbody>
      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Nombre</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><input name="nombre" id="nombre" class="required text" maxlength="50" type="text" value="<?=$datos[0]["nombre"]?>" size="50" <?= $disabled ?> ></td>
      </tr>  
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Nombre Página</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><input name="nombre_pagina" id="nombre_pagina" class="required text" maxlength="150" type="text" value="<?=$datos[0]["nombre_pagina"]?>" size="50" <?= $disabled ?> ></td>
      </tr>  

      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Fecha Inicio</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"> 
	       <input name="fechaInicio" type="text" id="fechaInicio" value="<?php echo $fechaInicio; ?>" size="8" readonly="readonly" class="required"/>
                <a href="javascript:show_calendar('document.form_alta.fechaInicio', document.form_alta.fechaInicio.value);" <?= $disabled ?> >
                        <img src="../_js/calendario2/cal.gif" width="16" height="16" border="0" />
				</a>                        
      </tr>  
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Fecha Fin</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"> 
	       <input name="fechaFin" type="text" id="fechaFin" value="<?php echo $fechaFin; ?>" size="8" readonly="readonly" class="required"/>
                <a href="javascript:show_calendar('document.form_alta.fechaFin', document.form_alta.fechaFin.value);" <?= $disabled ?> >
                        <img src="../_js/calendario2/cal.gif" width="16" height="16" border="0" />
				</a>                        
      </tr>        </tr>  
      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Logo Principal</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><input name="logoPrincipal" id="logoPrincipal" class="" type="file" <?= $disabled ?> ><? if ($datos[0]["logoPrincipal"] != "" ) { ?><a href="../logos/<?= $datos[0]["logoPrincipal"] ?>" target="_blank"> Imagen</a> <? } ?> </td>
      </tr>  
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Logo para el menú</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><input name="logoMenu" id="logoMenu" class="" maxlength="50" type="file" <?= $disabled ?> ><? if ($datos[0]["logoMenu"] != "" ) { ?><a href="../logos/<?= $datos[0]["logoMenu"] ?>" target="_blank"> Imagen</a> <? } ?> </td>
      </tr>  
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Logo P&aacute;gina</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><input name="logoPagina" id="logoPagina" class="" maxlength="50" type="file" <?= $disabled ?> ><? if ($datos[0]["logoPagina"] != "" ) { ?><a href="../logos/<?= $datos[0]["logoPagina"] ?>" target="_blank"> Imagen</a> <? } ?> </td>
      </tr>        
      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Activo</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><input type="checkbox"  <? if ($datos[0]["activo"] == "1" ) { ?> checked="checked" <? } ?> name="activo" id="activo" <?= $disabled ?> ></td>
      </tr> 
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Color</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last">
             <select name="color" id="color" class="validate-selection" <?= $disabled ?>>
				<option value="-1" >Seleccione Color...</option>		
				  <option  value="0" <? if( $datos[0]["color"] == 0) {?> selected="selected"<? } ?>>Rosa</option>						
				  <option  value="1" <? if( $datos[0]["color"] == 1) {?> selected="selected"<? } ?>>Verde</option>														
				  <option  value="2" <? if( $datos[0]["color"] == 2) {?> selected="selected"<? } ?>>Amarillo</option>														                  
			</select>      
        </td>                
	  </tr>         
      <? if ($_POST[id] > 0) { ?>
      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Orden</label></td>
        <td class="col_1 col_last"><?= $datos[0]["orden"] ?></td>
      </tr>              
    <? } ?>  
	</tbody>
	</table>
	</fieldset>

    <div class="submit_container">
   
   <? if ( $disabled  == "" ) { ?>
   	 <input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Guardar" /> 
    <? } ?>
    <!--    <input class="submit" type="button" value="Limpiar" onclick="javascript:limpiar('form_alta');" />-->
    <input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
    
    
    
    </div>
    </div>
</div>
</form>

</div>
<!-- indexer::continue -->


<div class="ce_text g4 xpln block">

	<p><strong>Datos del Torneo</strong><br>
	Ingrese los datos del Torneo.<br />
    El campo <b>Logo Principal</b> se utiliza en la Home<br />
    El campo <b>Logo para el menú</b> se utiliza en la para acceder a las páginas de dicho Torneo, son las im&aacute;genes chiquitas<br />    
    El campo <b>Logo P&aacute;gina</b> el el logo que se encuentra arriba a la izquierda en las p&aacute;ginas internas  que corresponden al torneo.
    </p>
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