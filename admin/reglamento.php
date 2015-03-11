<?	include_once "include/fechas.php";
	include_once "../model/reglamento.php";
    include_once "../model/fckeditor.class.php" ;
	
	
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	
	$oReglamento = new Reglamento();


	switch ($_POST["accion"]) {
	

		case "guardar":	
		
//			$data =   decodeUTF8($_POST);
			$data =   $_POST;

			$oReglamento->set($data);
			
			$oReglamento->modificar(1);
			
		
			break;

	}
	
	
	$datos = $oReglamento->getById(1);

    $oFCKeditor = new FCKeditor( "texto" ) ;

    $oFCKeditor -> BasePath = '../_js/FCKeditor/' ;

	$oFCKeditor -> Height = 850 ;

	$oFCKeditor -> Width = 700 ;

	$oFCKeditor -> ToolbarSet = "Default" ;

    $oFCKeditor -> InstanceName = "descripcion" ;

    $oFCKeditor -> Value = html_entity_decode($datos->descripcion) ;
	
	$disabled = "";


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
	   Reglamento</h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration g10 tableform block">

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
	<legend>Reglamento</legend>
	<table summary="Personal data" cellpadding="0" cellspacing="0">
  	<tbody>  
      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Descripción</label><span class="mandatory">*</span></td>
       </tr> 
      <tr class="odd">       
        <td class="col_1 col_last"><?= $oFCKeditor -> Create( ) ; ?></td>      </tr>   
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


<div class="clear"></div>

</div>

</div>

	<div id="clear"></div>

</div>

</div>

<? include("pie.php")?>


</body>

</html>