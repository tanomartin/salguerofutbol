<?	include_once "include/fechas.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.zonas.php";	
	include_once "../model/amistosos.php";
	include_once "../model/fechas.php";	
	include_once "../model/equipos.php";	
	include_once "../model/sedes.php";	
	include_once "include/control_session.php";
	
	$operacion = "Alta";
	if ($_POST["id"] != -1 && !isset($errorExiste)) {
		$operacion = "Modificaci&oacute;n";
		$oAmistoso = new Amistosos();
		$datos = $oAmistoso->get($_POST["id"]);
	}

	$disabled = "";
	
	if($_POST['accion'] == 'ver')
		$disabled = "disabled";
	
	if (isset($errorExiste)) {
		$error = "No se puede guardar el partido. Existe uno en el mismo día, horario, sede y cancha";
	} else {
		$error = "";
	}

	$oTorneo= new Torneos();
	$aTorneos = $oTorneo->get();

	$oSede= new Sedes();
	$aSedes = $oSede->get();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
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

	function validarPartido(formulario) {
		alert("lalala");
		return false;
	}

</script>
</head>
<body id="top" class="home">
<div id="wrapper">
  <!-- Header -->
  <div id="header">
    <div class="inside">
      <? include("top_menu.php"); ?>
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
            <h1>
              <?=$operacion?>
              del Amistoso </h1>
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
              <div class="formbody">
                <div class="ce_table">
                  <fieldset>
                  <legend>Datos del Amistoso </legend>
                  </fieldset>
				  <div id="error" style="color:#FF0000"><b><?=$error?></b></div>
                  <table summary="Personal data" cellpadding="0" cellspacing="0">
                    <tbody><tr class="even">
                        <td class="col_0 col_first"><label for="nombre">Fecha Partido</label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><input name="fechaPartido" type="text" id="fechaPartido" value="<?php echo cambiaf_a_normal($datos[0]["fechaPartido"]); ?>" size="10" readonly="readonly" class="required"/>
                          <? if($disabled != "disabled") { ?>  
						  		<a href="javascript:show_calendar('document.form_alta.fechaPartido', document.form_alta.fechaPartido.value);"> <img src="../_js/calendario2/cal.gif" width="16" height="16" border="0" /> </a>   
					  <? } ?>                      </tr>
                      <tr class="odd">
                        <td class="col_0 col_first"><label for="nombre">Hora del Partido</label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><input name="horaPartido" type="text" id="horaPartido" value="<?php echo $datos[0]["horaPartido"]; ?>" class="required" size="5"  <?= $disabled ?>/>                        </td>
                      </tr>
                      <tr class="even">
                        <td class="col_0 col_first"><label for="nombre">Sede</label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last">
						<select name="idSede" id='idSede' <?= $disabled ?> class="validate-selection">
                            <option value="-1">Seleccione una Sede...</option>
                            <?php for($i=0;$i<count($aSedes);$i++) { ?>
                            <option value="<?php echo $aSedes[$i]['id'] ?>" <?php if ($datos[0]["idSede"] ==   $aSedes[$i]['id'] ) echo "selected"; ?>><?php echo $aSedes[$i]['nombre'] ?> </option>
                            <?php } ?>
                      </select>                      </tr>
                      <tr class="odd">
                        <td class="col_0 col_first"><label for="nombre">Cancha</label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><input name="cancha" type="text" id="cancha" value="<?php echo $datos[0]["cancha"]; ?>" class="required" size="5"  <?= $disabled ?>/>                        </td>
                      </tr>
                      <tr class="even">
                        <td class="col_0 col_first"><label for="nombre">Equipo #1 </label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><span id="Equipo1List">
                          <select name="idEquipo1" id="idEquipo1" <?= $disabled ?> class="validate-selection" onChange="clearEquipo2('idEquipo2');return listOnChange('idEquipo1', '', 'Equipo2List','equipoAmistoso_data.php','advice4','idEquipo2','idEquipo2');" >
                            <option value="-1">Seleccione un Equipo...</option>
                            <?
						 if($datos[0]["idEquipo1"] || $operacion == "Alta") {
							$oEquipos = new Equipos();
							$aEquipos = $oEquipos->getEquiposSinTorneo();

							for ($i=0;$i<count($aEquipos);$i++) 
							{
						?>
                            <option <? if($aEquipos[$i]["id"] == $datos[0]["idEquipo1"]) echo "selected"; ?> value="<?=$aEquipos[$i]["id"]?>">
                            <?=$aEquipos[$i]["nombre"]."-".$aEquipos[$i]["zona"]?>
                            </option>
                            <?							
							}
						 }
						?>
                          </select>
                          <span id="advice3"> </span> </span> </td>
                      </tr>
                      <tr class="even">
                        <td class="col_0 col_first"><label for="nombre">Equipo #2 </label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><span id="Equipo2List">
                          <select name="idEquipo2" id="idEquipo2" <?= $disabled ?> class="validate-selection" >
                            <option value="-1">Seleccione antes un Equipo #1...</option>
                            <?
                                 if($datos[0]["idEquipo2"]) {
                                    $oEquipos = new Equipos();
                                    $aEquipos = $oEquipos->getEquiposSinTorneo($datos[0]["idEquipo1"]);
        
                                    for ($i=0;$i<count($aEquipos);$i++) 
                                    {
                                ?>
                            <option <? if($aEquipos[$i]["id"] == $datos[0]["idEquipo2"]) echo "selected"; ?> value="<?=$aEquipos[$i]["id"]?>">
                           <?=$aEquipos[$i]["nombre"]."-".$aEquipos[$i]["zona"]?>
                            </option>
                            <?							
                                    }
                                 }
                                ?>
                          </select>
                          <span id="advice4"> </span> </span> </td>
                      </tr>
                    </tbody>
                  </table>
                  </fieldset>
                  <div class="submit_container">
                    <? if ( $disabled  == "" ) { ?>
                    <input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Guardar" />
                    <? } ?>
                    <input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
                  </div>
                </div>
              </div>
            </form>
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
