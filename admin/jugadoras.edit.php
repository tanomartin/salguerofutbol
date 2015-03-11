<?	include_once "include/fechas.php";
	include_once "../model/jugadoras.php";
	include_once "../model/equipos.php";	
	include_once "../model/posiciones.php";	
	
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$operacion = "Alta";

	if ($_POST["id"] != -1) {
	
		$operacion = "Modificaci&oacute;n";

		$oJugadora= new Jugadoras();
		$datos = $oJugadora->get($_POST["id"]);

//		$datos = decodeUTF8($datos);	
	}
	$disabled = "";
	
	if( $_POST['accion'] == 'ver')
		$disabled = "disabled";


	$oEquipo= new Equipos();
	$aEquipos = $oEquipo->get();

	$oPosicion= new Posiciones();
	$aPosicion = $oPosicion->get();

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
	   del Jugador</h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration g8 tableform block">

<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post"  enctype="multipart/form-data"> 


<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
<input type="hidden" name="accion" value="guardar" />

 <!-- Filtros -->
     <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
     <input name="fequipo" type="hidden" style="width:100px" value="<?=$_POST["fequipo"]?>"  />
<!-- Fin filtros -->

<!-- Parametros menu -->
<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
<!--     -->

<div class="formbody">

	<div class="ce_table">
	
	<fieldset>
	<legend>Datos del Equipo

	</legend><table summary="Personal data" cellpadding="0" cellspacing="0">
  	<tbody>
      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Nombre</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><input name="nombre" id="nombre" class="required text" maxlength="50" type="text" value="<?=$datos[0]["nombre"]?>" size="50"  <?= $disabled ?>></td>
      </tr>  
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Equipo</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last">
         <select name="idEquipo" id='idEquipo' <?= $disabled ?> class="validate-selection" >
            <option value="-1">Seleccione un Equipo...</option>
		 	<?php for($i=0;$i<count($aEquipos);$i++) { ?>	
				<option value="<?php echo $aEquipos[$i]['id'] ?>" <?php if ($datos[0]["idEquipo"] ==   $aEquipos[$i]['id'] ) echo "selected"; ?>><?php echo $aEquipos[$i]['nombre'] ?>
                </option>
             <?php } ?>	   
         	</select>
         </td>   
      </tr>  
      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Fecha Nacimiento:</label></td>
        <td class="col_1 col_last">
            <input name="fechaNac" type="text" id="fechaNac" value="<?php echo cambiaf_a_normal($datos[0]["fechaNac"]); ?>" size="10" readonly="readonly" />
                <a href="javascript:show_calendar('document.form_alta.fechaNac', document.form_alta.fechaNac.value);">
                        <img src="../_js/calendario2/cal.gif" width="16" height="16" border="0" />
				</a>
        </td> 
     </tr>   
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Posici&oacute;n</label></td>
        <td class="col_1 col_last">
         <select name="idPosicion" id='idPosicion' <?= $disabled ?>  >
            <option value="-1">Seleccione una Posici&oacute;n...</option>
		 	<?php for($i=0;$i<count($aPosicion);$i++) { ?>	
				<option value="<?php echo $aPosicion[$i]['id'] ?>" <?php if ($datos[0]["idPosicion"] ==   $aPosicion[$i]['id'] ) echo "selected"; ?>><?php echo $aPosicion[$i]['nombre'] ?>
                </option>
             <?php } ?>	   
         	</select>
         </td>   
      </tr>  

      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Goles</label></td>
        <td class="col_1 col_last"> 
	       <input name="goles" type="text" id="goles" value="<?php echo $datos[0]["goles"]; ?>" class="validate-digits" size="3"  <?= $disabled ?>/>
         </td>
       </tr>    
      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Foto Preview</label></td>
        <td class="col_1 col_last"><input name="fotoPreview" id="fotoPreview" class="" type="file"  <?= $disabled ?> ><? if ($datos[0]["fotoPreview"] != "" ) { ?><a href="../fotos_jugadoras/<?= $datos[0]["fotoPreview"] ?>" target="_blank"> Imagen</a> <? } ?> </td>
      </tr>  
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Foto Grande</label></td>
        <td class="col_1 col_last"><input name="fotoGrande" id="fotoGrande" class="" maxlength="50" type="file"  <?= $disabled ?>><? if ($datos[0]["fotoGrande"] != "" ) { ?><a href="../fotos_jugadoras/<?= $datos[0]["fotoGrande"] ?>" target="_blank"> Imagen</a> <? } ?> </td>
      </tr>  
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

	<p><strong>Datos de la Jugadora</strong></p>
	<p>Ingrese los datos de la Jugadora</p>
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