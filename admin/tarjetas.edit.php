<?	include_once "include/fechas.php";
	include_once "../model/fechas.php";	
	include_once "../model/equipos.php";	
	include_once "../model/jugadoras.php";	
	include_once "../model/resultados.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	
	$operacion = "Tarjetas";

	$oEquipos = new Equipos();
	$aEquipos1 = $oEquipos->get($_POST['id']);


	$oFechas = new Fechas();
	$aFechas = $oFechas->getIdTorneoCat($aEquipos1[0]['idTorneoCat']);


	$oJugadora = new Jugadoras();
	$aJugadoras1 = $oJugadora->getByEquipo( $_POST['id']);

	$oResultados = new Resultados();

	for ($i=0; $i<count($aJugadoras1); $i++) {

		$tarj =  $oResultados->getTarjetasByIdJugadora($aJugadoras1[$i][id]);
		$arreglo[$aJugadoras1[$i][id]][amarillas] = $tarj[0][amarillas];
		$arreglo[$aJugadoras1[$i][id]][rojas] = $tarj[0][rojas];		
		
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
	<h1><?=$operacion?></h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration  tableform block">

<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post"  enctype="multipart/form-data"> 


<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
<input name="id_torneo" id="id_torneo"  value="<?= $aFechas[0]["id_torneo"]?>" type="hidden" />
<input name="idEquipo1" id="idEquipo1"  value="<?= $_POST['id']?>" type="hidden" />
<input name="id_categoria" id="id_categoria"  value="<?= $aFechas[0]["id_categoria"]?>" type="hidden" />
<input type="hidden" name="accion" value="guardarResultado" />

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
	<legend>Datos 

	</legend>
    </fieldset>
<table summary="Personal data" cellpadding="0" cellspacing="0">
  	<tbody>      
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Torneo</label></td>
        <td class="col_1 col_last"><?= $aFechas[0]['torneo'] ?>
         </td>   
      </tr>  

      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Categoría</label></td>
        <td class="col_1 col_last"><?= $aFechas[0]['categoria'] ?>
        </td>    
      </tr>  
        
      <tr class="odd">
		 <td class="col_0 col_first"><label for="nombre">Equipo </label></td>
         <td class="col_1 col_last"><?= $aEquipos1[0]["nombre"] ?></td>
      </tr>  
      <tr class="even">
          <td class="col_0 col_first"><label for="nombre">Detalle Por Jugadoras</label></td>
          <td class="col_1 col_last">
                <table  width="100%">
                	<tr>
                    	<td>Jugadora</td>
                        <td>Amarillas</td>
                        <td></td>                        
                        <td>Rojas</td>
                        <td></td>    
                        <td>Observaciones</td>                                                
                    </tr>
                    <? for($i=0;$i<count($aJugadoras1);$i++) { ?>
	                   <tr>
    	               	<td><?= $aJugadoras1[$i][nombre]?></td>
        	            <td><?= $arreglo[ $aJugadoras1[$i][id]][amarillas] - $aJugadoras1[$i][amarillas] ?></td>                                                                        
        	            <td><input name="<?= $aJugadoras1[$i][id]?>_amarillas" type="text" size="5" value=""/></td>                                                
        	            <td><?= $arreglo[ $aJugadoras1[$i][id]][rojas] - $aJugadoras1[$i][rojas] ?></td>                                                                                                
        	            <td><input name="<?= $aJugadoras1[$i][id]?>_rojas" type="text" size="5" value=""/></td>                                                
                      <td><input name="<?= $aJugadoras1[$i][id]?>_obs" type="text" size="50" value=""/></td>                                                
	                   </tr>
                    <? } ?>   
                 </table>        
              </td>
          </tr>
	</tbody>
	</table>
	</fieldset>

    <div class="submit_container">
   	 <input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Guardar" /> 
<!--    <input class="submit" type="button" value="Limpiar" onclick="javascript:limpiar('form_alta');" />-->
    <input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
    
    
    
    </div>
    </div>
</div>
</form>

</div>
<!-- indexer::continue -->
<!--

<div class="ce_text g4 xpln block">

	<p><strong>Datos del Fixture</strong><br>
	Ingrese los datos del Fixture.</p>
	<p>Los campos marcados con <span class="mandatory">*</span> son de ingreso obligatorio.</p>

</div>
-->
<div class="clear"></div>

</div>

</div>

	<div id="clear"></div>

</div>

</div>

<? include("pie.php")?>


</body>

</html>