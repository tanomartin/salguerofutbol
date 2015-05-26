<?
	include_once "../model/torneos.php";
	include_once "../model/torneos.zonas.php";	
	include_once "../model/fechas.php";	
	include_once "include/control_session.php";

	$operacion = "Alta";

	if ($_POST["id"] != -1) {
		$operacion = "Modificaci&oacute;n";
		$oFecha= new Fechas();
		$datos = $oFecha->get($_POST["id"]);
	}
	
	$disabled = "";
	if( $_POST['accion'] == 'ver')
		$disabled = "disabled";

	$oTorneo= new Torneos();
	$aTorneos = $oTorneo->get();
	
	if (isset($errorExiste)) {
		$error = "No se puede guardar la fecha. Existe una con el mismo nombre en esta zona";
		if ($_POST["id"] == -1) {
			$datos[0]['idTorneoZona'] = $datos[0]['idTorneoCat'];
			$datos[0]['id_torneo'] = $datos[0]['idTorneo'];
		}
	} else {
		$error = "";
	}

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
              de Fecha</h1>
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
              <div class="formbody">
                <div class="ce_table">
                  <fieldset>
                  <legend>Datos de la Fecha </legend>
				  </fieldset>
				  <div id="error" style="color:#FF0000"><b><?=$error?></b></div>
                  <table summary="Personal data" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr class="even">
                        <td class="col_0 col_first"><label for="nombre">Nombre</label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><input name="nombre" id="nombre" class="required text" maxlength="50" type="text" value="<?=$datos[0]["nombre"]?>" size="50"  <?= $disabled ?>></td>
                      </tr>
                      <tr class="odd">
                        <td class="col_0 col_first"><label for="nombre">Torneo</label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><select name="idTorneo" id='idTorneo' <?= $disabled ?> class="validate-selection" onChange="clearCategoria('idTorneoCat');
         	return listOnChange('idTorneo', '','categoriaList','zona_data2.php','advice1','idTorneoCat','idTorneoCat');" >
                            <option value="-1">Seleccione un Torneo...</option>
                            <?php for($i=0;$i<count($aTorneos);$i++) { ?>
                            <option value="<?php echo $aTorneos[$i]['id'] ?>" <?php if ($datos[0]["id_torneo"] ==   $aTorneos[$i]['id'] ) echo "selected"; ?>><?php echo $aTorneos[$i]['nombre'] ?> </option>
                            <?php } ?>
                          </select>
                        </td>
                      </tr>
                      <tr class="even">
                        <td class="col_0 col_first"><label for="nombre">Zona</label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><span id="categoriaList">
                          <select name="idTorneoCat" id="idTorneoCat" <?= $disabled ?> class="validate-selection" >
                            <option value="-1">Seleccione antes un Torneo...</option>
                            <?
						 if($datos[0]["id_torneo"]) {
							$oTorneoZona = new TorneoZona();
							$aTorneoZona = $oTorneoZona->getByTorneoFechas($datos[0]["id_torneo"]);

							for ($i=0;$i<count($aTorneoZona);$i++) 
							{
						?>
                            <option <? if($aTorneoZona[$i]["id"] == $datos[0]["idTorneoZona"]) echo "selected"; ?> value="<?=$aTorneoZona[$i]["id"]?>">
                            <?=$aTorneoZona[$i]["nombreCorto"]?>
                            <? if ( $aTorneoZona[$i]["nombreCat"] != "" ){ echo "- ". $aTorneoZona[$i]["nombreCat"]; } ?>
                            </option>
                            <?							
							}
						 }
						?>
                          </select>
                          <span id="advice1"> </span> </span> </td>
                      </tr>
                      <tr class="odd">
                        <td class="col_0 col_first"><label for="nombre">Fecha Inicio<span class="mandatory">*</span></label></td>
                        <td class="col_1 col_last"><input name="fechaIni" type="text" id="fechaIni" value="<?php echo cambiaf_a_normal($datos[0]['fechaIni']);?>" size="10" readonly="readonly" />
                          <a href="javascript:show_calendar('document.form_alta.fechaIni', document.form_alta.fechaIni.value);"> <img src="../_js/calendario2/cal.gif" width="16" height="16" border="0" /> </a> </td>
                      </tr>
                      <tr class="even">
                        <td class="col_0 col_first"><label for="nombre">Fecha Fin</label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><input name="fechaFin" type="text" id="fechaFin" value="<?php echo cambiaf_a_normal($datos[0]['fechaFin'])?>" size="10" readonly="readonly" />
                          <a href="javascript:show_calendar('document.form_alta.fechaFin', document.form_alta.fechaFin.value);"> <img src="../_js/calendario2/cal.gif" width="16" height="16" border="0" /> </a> </td>
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
            <p><strong>Datos del Fecha </strong><br>
              Ingrese los datos del Fecha.</p>
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
