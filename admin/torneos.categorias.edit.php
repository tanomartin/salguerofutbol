<?
	include_once "../model/torneos.php";
	include_once "../model/categorias.php";

   //instancio editor

  $operacion = "Alta";

	if ($_POST["id_torneo_categoria"] != -1) {
	
		$operacion = "Modificaci&oacute;n";

	}



  if ($_POST["id_torneo_categoria"] != -1)
		$id_torneo_categoria = $_POST["id_torneo_categoria"];
  else 	
		$id_torneo_categoria = time();

	$oObj = new Torneos();
	$datosTorneo = $oObj->get($_POST['id']);


	$oObj1 = new Categorias();
	$datosCat = $oObj1->getByCategoriasDisponibles($_POST['id']);
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
	
		document.form_alta.accion.value = "info";		
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
	<h1><?=$operacion?> de Categor&iacute;a - <?= $datosTorneo[0]['nombre'] ?></h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration g8 tableform block">

<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post">

			<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
			<input name="id_torneo" id="id_torneo"  value="<?=$_POST["id"]?>" type="hidden" />
			<input name="id_torneo_categoria" id="id_torneo_categorial"  value="<?=$_POST["id_torneo_categoria"]?>" type="hidden" />
			<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
			<input type="hidden" name="accion" value="guardarCategoria" />
			<input name="idtemporal" id="idtemporal"  value="<?=$id_torneo_categoria?>" type="hidden" />

        			<!-- Parametros menu -->
        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
                    <!--     -->
	<div class="ce_table">
	
	<fieldset>
   <table width="108%" cellpadding="0" cellspacing="0" summary="Personal data">
  	<tbody>
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Categor&iacute;a</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"> 
        	<select name="id_categoria" id="id_categoria" class="validate-selection" >
							<option value="-1" >Seleccione Categor&iacute;a...</option>		
						<?
							for ($i=0;$i<count($datosCat);$i++) 
							{
								
							?>	
							     <option  value="<?=$datosCat[$i]["id"]?>" ><?=$datosCat[$i]["nombreLargo"]?></option>						
								
						<?
                        	}
						?>
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

	<p><strong>Categor&iacute;as  del Torneo</strong><br>
	Ingrese la categor&iacute;a  del torneo.</p>
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
