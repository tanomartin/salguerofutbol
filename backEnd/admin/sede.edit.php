<?	include_once "include/config.inc.php";
	include_once "../model/sedes.php";
	include_once "include/control_session.php";
	
	$operacion = "Alta";

	if ($_POST["id"] != -1) {
		$operacion = "Modificaci&oacute;n";
		$oSedes= new Sedes();
		$datos = $oSedes->get($_POST["id"]);
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
              de Sedes</h1>
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
                  <legend>Datos de la Sede </legend>
                  <table summary="Personal data" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr class="even">
                        <td class="col_0 col_first"><label for="id_opcion">Nombre</label>
                          <span class="mandatory">*</span></td>
                        <td class="col_1 col_last"><input name="nombre" id="nombre" class="required text" maxlength="100"  size="50" type="text" value="<?=$datos[0]["nombre"]?>"></td>
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
            <p><strong>Datos de la Sede </strong><br>
              Ingrese los datos de la Sede</p>
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
