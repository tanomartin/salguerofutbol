<?	include_once "include/config.inc.php";
	include_once "../model/torneos.php";
	include_once "../model/equipos.php";
	include_once "include/control_session.php";
	
	$operacion = "Alta";

	if ($_POST["id"] != -1) {
		$operacion = "Modificaci&oacute;n";
		$oExpusados = new Expulsados();
		$datos = $oExpusados->get($_POST["id"]);
	}
	
	$oTorneo= new Torneos();
	$aTorneos = $oTorneo->get();
	
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
              de Sancion </h1>
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
              <div class="formbody">
                <div class="ce_table">
                  <fieldset>
                  <legend>Datos de la Sancion </legend>
                  <table summary="Personal data" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr class="even">
                        <td class="col_0 col_first"><label for="id_opcion">Nombre</label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last">
							<input name="nombre" id="nombre" class="required text" maxlength="100"  size="50" type="text" value="<?=$datos[0]["nombre"]?>">
						</td>
                      </tr>
					  <tr class="even">
					   <td class="col_0 col_first"><label for="nombre">Torneo</label>
                          <span class="mandatory">*</span></td>
                      <td class="col_1 col_last">
					  	<select name="idTorneo" id="idTorneo" <?= $disabled ?> class="validate-selection" onchange="clearEquipo1('idEquipo'); return listOnChange('idTorneo', '','Equipo1List','equiposGoleadores_data.php','advice3','idEquipo','idEquipo');" >
                          <option value="-1">Seleccione un Torneo...</option>
                          <?php for($i=0;$i<count($aTorneos);$i++) { ?>
                          <option value="<?php echo $aTorneos[$i]['id'] ?>" <?php if ($datos[0]["idTorneo"] ==   $aTorneos[$i]['id'] ) echo "selected"; ?>><?php echo $aTorneos[$i]['nombre'] ?> </option>
                          <?php } ?>
                        </select>
					  </td>
					  </tr>
                      <tr class="even">
                        <td class="col_0 col_first"><label for="nombre">Equipo </label>
                            <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><span id="Equipo1List">
                          <?
							$oEquipos = new Equipos();
							$aEquipos = $oEquipos->get();?>
						  <select name="idEquipo" id="idEquipo" <?= $disabled ?> class="validate-selection" >
                            <option value="-1">Seleccione un Equipo...</option>
                        <?
							for ($i=0;$i<count($aEquipos);$i++) { ?>
								<option <? if($aEquipos[$i]["id"] == $datos[0]["idEquipo"]) echo "selected"; ?> value="<?=$aEquipos[$i]["id"]?>">
								<?=$aEquipos[$i]["nombre"]." - ".$aEquipos[$i]["zona"]?>
								</option>
                         <? } ?>
                          </select>
                          <span id="advice3"> </span> </span> </td>
                      </tr>
                      <tr class="even">
                        <td class="col_0 col_first"><label for="label">Sancion </label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><input name="sancion" id="sancion" class="required text" maxlength="50"  size="50" type="text" value="<?=$datos[0]["sancion"]?>" /></td>
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
          </div>
          <!-- indexer::continue -->
          <div class="ce_text g4 xpln block">
            <p><strong>Datos del Expulsado </strong><br>
              Ingrese los datos del Expulsado</p>
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
