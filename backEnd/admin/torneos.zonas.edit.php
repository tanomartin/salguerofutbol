<?
	include_once "../model/torneos.php";
	include_once "../model/zonas.php";

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


	$oObj1 = new Zonas();
	$datosCat = $oObj1->getByCategoriasDisponibles($_POST['id']);
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
              de Zona -
              <?= $datosTorneo[0]['nombre'] ?>
            </h1>
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
              <div class="ce_table">
                <fieldset>
                <table width="108%" cellpadding="0" cellspacing="0" summary="Personal data">
                  <tbody>
                    <tr class="odd">
                      <td class="col_0 col_first"><label for="nombre">Zona</label>
                        <span class="mandatory">*</span></td>
                      <td class="col_1 col_last"><select name="id_categoria" class="validate-selection" id="id_categoria" >
                          <option value="-1" selected="selected" >Seleccione Zona...</option>
                          <?
							for ($i=0;$i<count($datosCat);$i++) 
							{							
							?>
                          <option  value="<?=$datosCat[$i]["id"]?>" >
                          	<?=$datosCat[$i]["nombreCorto"]?>
                          </option>
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
            <p><strong>Zonas  del Torneo</strong><br>
              Ingrese la zona  del torneo.</p>
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
