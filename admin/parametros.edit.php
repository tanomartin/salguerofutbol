<?	include_once "include/config.inc.php";
	include_once "../model/parametros.php";
	
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	
	$operacion = "Modificaci&oacute;n";

	$oParametros = new Parametro();
	$datos = $oParametros->get($_POST["id"]);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>

<!-- base href="http://www.typolight.org/" -->
<title>Panel de Control</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
	<h1><?=$operacion?> de Parametros</h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration g8 tableform block">

<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post">


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

<!-- orden -->
<input type="hidden" name="orden" value="<?=$_POST["orden"]?>" />
<input type="hidden" name="dir" value="<?=$_POST["dir"]?>" />
<!-- orden -->


<div class="formbody">

	<div class="ce_table">
	
	<fieldset>
	<legend>Parametros de campos</legend>

	<table summary="Personal data" cellpadding="0" cellspacing="0">
  	<tbody>

      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Nombre</label></td>
        <td class="col_1 col_last"><?=$datos[0]["nombre"]?></td>
      </tr>
      
       <tr class="even">
        <td class="col_0 col_first"><label for="valor">Valor</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><textarea name="valor" id="valor" class="required text" cols="50" rows="5"><?=$datos[0]["valor"]?></textarea></td>
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
</div>
</form>

<script>

	document.form_alta.valor.focus();

</script>

</div>
<!-- indexer::continue -->


<div class="ce_text g4 xpln block">

	<p><strong>Datos del Par&aacute;metro</strong><br>
	Ingrese el valor del Par&aacute;metro. </p>
	<p>Los campos marcados con <span class="mandatory">*</span> son de ingreso obligatorio.</p>

</div>

<div class="clear"></div>

</div>

</div>

	<div id="clear"></div>

</div>

</div>

<? include("pie.php")?>

</div>
</body>

</html>
