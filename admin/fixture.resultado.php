<?	include_once "include/fechas.php";
	include_once "../model/fixture.php";
	include_once "../model/fechas.php";	
	include_once "../model/equipos.php";	
	include_once "../model/jugadoras.php";	
	include_once "../model/resultados.php";	
		
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	
	$operacion = "Resultado";

	$oFixture= new Fixture();
	$datos = $oFixture->get($_POST["id"]);

	$oFechas = new Fechas();
	$aFechas = $oFechas->get($datos[0]["idFecha"]);

	$oEquipos = new Equipos();
	$aEquipos1 = $oEquipos->get($datos[0]["idEquipo1"]);
	$aEquipos2 = $oEquipos->get($datos[0]["idEquipo2"]);


	$oJugadora = new Jugadoras();
	$aJugadoras1 = $oJugadora->getByEquipo( $datos[0]["idEquipo1"]);
	$aJugadoras2 = $oJugadora->getByEquipo( $datos[0]["idEquipo2"]);

	$oResultado= new Resultados();
	$resultados = $oResultado->get($_POST["id"]);
	
	if ( $resultados ) {
	  foreach($resultados as $key => $valores){
		$aResultado[ $valores['idJugadora'] ]['goles'] =  ($valores['goles']>0)?$valores['goles']:'';
		$aResultado[ $valores['idJugadora'] ]['amarilla'] =  ($valores['tarjeta_amarilla']>0)?$valores['tarjeta_amarilla']:'';		
		$aResultado[ $valores['idJugadora'] ]['roja'] =  ($valores['tarjeta_roja']>0)?$valores['tarjeta_roja']:'';		
		$aResultado[ $valores['idJugadora'] ]['mejor'] =  $valores['mejor_jugadora'];
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
	   del Fixture</h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration  tableform block">

<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post"  enctype="multipart/form-data"> 


<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
<input name="idFixture" id="idFixture"  value="<?= $datos[0]["id"]?>" type="hidden" />
<input name="idEquipo1" id="idEquipo1"  value="<?= $datos[0]["idEquipo1"]?>" type="hidden" />
<input name="idEquipo2" id="idEquipo2"  value="<?= $datos[0]["idEquipo2"]?>" type="hidden" />
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
	<legend>Datos del Fixture

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
        <td class="col_0 col_first"><label for="nombre">Fecha</label></td>
        <td class="col_1 col_last"> <?= $aFechas[0]['nombre'] ?>
        </td>
      <tr class="even">
		 <td class="col_0 col_first"><label for="nombre">Equipo #1 </label></td>
         <td class="col_1 col_last"><?= $aEquipos1[0]["nombre"] ?></td>
      </tr>  
      <tr class="odd">
          <td class="col_0 col_first"><label for="nombre">Detalle Por Jugadoras</label></td>
          <td class="col_1 col_last">
                <table  width="100%">
                	<tr>
                    	<td>Jugadora</td>
                		<td>Goles</td>
                        <td>Amariila</td>
                        <td>Roja</td>
                        <td>MJ</td>
                    </tr>
                    <? for($i=0;$i<count($aJugadoras1);$i++) { ?>
	                   <tr>
    	               	<td><?= $aJugadoras1[$i][nombre]?></td>
        	            <td><input name="<?= $aJugadoras1[$i][id]?>_goles" type="text" size="5" value="<?= $aResultado[$aJugadoras1[$i][id]][goles]  ?>"/></td>
            	        <td><input name="<?= $aJugadoras1[$i][id]?>_amarillas" type="text" size="5" value="<?= $aResultado[$aJugadoras1[$i][id]][amarilla]  ?>"/></td>
                        <td><input name="<?= $aJugadoras1[$i][id]?>_rojas" type="text" size="5" value="<?= $aResultado[$aJugadoras1[$i][id]][roja]  ?>"/></td>
                        <td><input type="radio"  name="mejor_jugadora" value="<?= $aJugadoras1[$i][id]?>" <? if ($aResultado[$aJugadoras1[$i][id]][mejor] ==  'S') echo  'checked="checked"'; ?> ></td>
	                   </tr>
                    <? } ?>   
                 </table>        
              </td>
          </tr>
          <tr class="even">
                <td class="col_0 col_first"><label for="nombre">Equipo #2 </label><span class="mandatory">*</span></td>
                <td class="col_1 col_last"><?= $aEquipos2[0]["nombre"] ?>
               </td>
          </tr> 
      <tr class="odd">
          <td class="col_0 col_first"><label for="nombre">Detalle Por Jugadoras</label></td>
          <td class="col_1 col_last">
                <table  width="100%">
                	<tr>
                    	<td>Jugadora</td>
                		<td>Goles</td>
                        <td>Amariila</td>
                        <td>Roja</td>
                        <td>MJ</td>
                    </tr>
                    <? for($i=0;$i<count($aJugadoras2);$i++) { ?>
	                   <tr>
    	               	<td><?= $aJugadoras2[$i][nombre]?></td>
        	            <td><input name="<?= $aJugadoras2[$i][id]?>_goles" type="text" size="5" value="<?= $aResultado[$aJugadoras2[$i][id]][goles]  ?>"/></td>
            	        <td><input name="<?= $aJugadoras2[$i][id]?>_amarillas" type="text" size="5" value="<?= $aResultado[$aJugadoras2[$i][id]][amarilla]  ?>"/></td>
                        <td><input name="<?= $aJugadoras2[$i][id]?>_rojas" type="text" size="5" value="<?= $aResultado[$aJugadoras2[$i][id]][roja]  ?>"/></td>
                        <td><input type="radio"  name="mejor_jugadora" value="<?= $aJugadoras2[$i][id]?>" <? if ($aResultado[$aJugadoras2[$i][id]][mejor] ==  'S') echo  'checked="checked"'; ?> ></td>                                             
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