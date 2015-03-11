<?	include_once "include/config.inc.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$oObj = new Torneos();
	$datosTorneo = $oObj->get($_POST['id']);

	$oObj = new TorneoCat();
	$datos = $oObj->getByTorneo($_POST['id']);

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

	function nuevo(){

		document.frm_listado.accion.value = "editarCategoria";
		document.frm_listado.id_torneo_categoria.value = "-1";
		document.frm_listado.submit();
		
	}

	function subcategorias(id){
		
		document.frm_listado.accion.value = "infoSubcategoria";
		document.frm_listado.id_torneo_categoria.value = "-1";
		document.frm_listado.id_categoria.value = id;		
		document.frm_listado.submit();
		
	}

	function borrar(id){
		
		document.frm_listado.accion.value = "borrarCategoria";
		document.frm_listado.id_torneo_categoria.value = id;
		document.frm_listado.submit();
		
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

		<div class="mod_article block" id="home">

			<div class="ce_text block">

				<div class="mod_listing ce_table listing block" id="partnerlist">

   
             <form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
                    <input type="hidden" name="_pag" value="<?=$_POST["_pag"]?>" />
                    <input type="hidden" name="id" value="<?=$_POST["id"]?>" />
                    <input type="hidden" name="id_torneo_categoria" value="" />
					<input type="hidden" name="id_categoria" value="" />                    
                    <input type="hidden" name="accion" value="" />
        
        			<!-- Parametros menu -->
        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
                    <!--     -->
                    
                 
				<div style="float:left"> <h2>Torneo: <?= $datosTorneo[0]['nombre'] ?></h2></div>
				<div style="margin-right:10px; margin-bottom:10px; float:right" >
            	<input class="button" onclick="javascript:nuevo()" type="button" value="Nueva Categor&iacute;a" />
            </div>
			<br/>
			<table width="928">
				
				<tr>
					<th >Categor&iacute;as</th>
					<th width="10%">Opciones</th>
				</tr>

				<? if (count($datos) == 0) { ?>
				
				
				<tr>
						<td colspan="4" align="center">No existen categor&iacute;as para este Torneo</td>
			  </tr>
				
               
				<? } else { 
				 
					for ( $i = 0; $i < count($datos); $i++ ) {

				?>


					<tr style="vertical-align:middle" >
						<td align="left"><?=$datos[$i]["nombreLargo"]?></td>
                      <td nowrap>
					    <a href="javascript:subcategorias(<?=$datos[$i]["id_categoria"]?>);"><img border="0" src="images/categorias.gif" alt="SubCategorias" title="SubCategorias"  width="20" height="20"/></a>
						<a href="javascript:borrar(<?=$datos[$i]["id"]?>);"><img border="0" src="images/icono-eliminar.gif" alt="eliminar" title="eliminar" /></a>
	
					  </td>
					</tr>

				<? } }?>
			</table>

		</form>

		</div>

		</div>

	</div>
 
</div>
 
	<div id="clear"></div>

</div>

</div>

<? include("pie.php")?>


</body>

</html>