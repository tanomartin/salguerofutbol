<?	include_once "include/fechas.php";
	include_once "../model/torneos.categorias.php";	
	include_once "../model/equipos.php";	
	
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$operacion = "Alta";


	$oTorneoCat= new TorneoCat();
	$aTorneos = $oTorneoCat->getCategoriasCompletas($_POST['id']);

	$oEquipo= new Equipos();
	$aEquipos = $oEquipo->getByCategoria($_POST['id']);

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
	   Jugadora Equipo Ideal</h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration  tableform block">

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
	<legend>Datos de la Jugadora

	</legend>
    </fieldset>
<table summary="Personal data" cellpadding="0" cellspacing="0">
  	<tbody>      
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Torneo</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last">
         <?= $aTorneos[0]['nombreTorneo'] ?>
         </td>   
      </tr>  

      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Categoría</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"> <?= $aTorneos[0]['nombreLargo'] ?><? if($aTorneos[0]['nombreCat'] != ""){ echo " - " .$aTorneos[0]['nombreCat'] ; } ?></td>    
      </tr>  
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Equipo</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"> 
				<span id="fechaList">
				<select name="idEquipo" id="idEquipo"  class="validate-selection"  onchange="clearJugadoras('idJugadora');listOnChange('idEquipo', '', 'JugadoraList','jugadoras_data.php','advice3','idJugadora','idJugadora');">
					<option value="-1">Seleccione un Equipo...</option>
						<?
							for ($i=0;$i<count($aEquipos);$i++) 
							{
						?>	
							 <option  value="<?=$aEquipos[$i]["id"]?>"><?=$aEquipos[$i]["nombre"]?></option>
							
						<?							
							}
						
						?>
						</select> 
            <span id="advice2"> </span>
			</span>	
        </td>
          

      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Jugadora </label><span class="mandatory">*</span></td>
        <td class="col_1 col_last">
          <span id="JugadoraList">
            <select name="idJugadora" id="idJugadora"  class="validate-selection" >
              <option value="-1">Seleccione antes un Equipo...</option>
              </select>
            <span id="advice3"> </span>
            </span>	
          </td>
      </tr>  
            <tr class="odd">
                <td class="col_0 col_first"><label for="nombre">Posici&oacute;n en la Cancha </label><span class="mandatory">*</span></td>
                <td class="col_1 col_last">
                    <select name="idPosicion" id="idPosicion" >
                    	<? for($i=1; $i<=5; $i++) { ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
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