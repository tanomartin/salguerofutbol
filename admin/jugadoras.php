<?	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/jugadoras.php";
	include_once "../model/torneos.categorias.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$menu = "Secciones";

	//print_r($_POST);

	switch ($_POST["accion"]) {
	

		case "editar":
		
			include("jugadoras.edit.php");
			exit;
			break;
			
		case "ver":
		
			include("jugadoras.edit.php");
			exit;
			break;
			
		case "guardar":	
		
//			$data =   decodeUTF8($_POST);
			$data =   $_POST;
			$files = $_FILES;

			$oObj = new Jugadoras();

			$oObj->set($data);
	
			if($_POST["id"] == "-1") { // Tiene el valor de session_id()
				$oObj->insertar($files);
			} else {
				$oObj->actualizar($files);
			}
		
			break;

		case "borrar":
		
			//$data =   decodeUTF8($_POST);
			$data =   $_POST;
			$oObj = new Jugadoras();

			$oObj->set($data);
	
			$oObj->eliminar();
	
			$_POST["_pag"] = ($_POST["ult"] == "S") ? $_POST["_pag"] - 1 : $_POST["_pag"];

			break;

	}
	
	
	
	//Paginacion
	$cant   = 10;
  	$pag    = ($_POST['_pag']>0) ? $_POST['_pag'] : 1;
	$inicio = ($pag - 1) * $cant;
    $fin    = $inicio + $cant;
	// fin Paginacion

	$total = 0;
	$oObj = new Jugadoras();
	$datos = $oObj->getPaginado($_REQUEST, $inicio, $cant, $total);


	$esultimo = (count($datos) == 1)? "S" : "N" ;
	

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

		document.frm_listado.accion.value = "editar";
		document.frm_listado.id.value = "-1";
		document.frm_listado.submit();
		
	}

	function editar(id){
		
		document.frm_listado.accion.value = "editar";
		document.frm_listado.id.value = id;
		document.frm_listado.submit();
		
	}
	
	function ver(id){
		
		document.frm_listado.accion.value = "ver";
		document.frm_listado.id.value = id;
		document.frm_listado.submit();
		
	}
	
	function borrar(id){
		
		document.frm_listado.accion.value = "borrar";
		document.frm_listado.id.value = id;
		document.frm_listado.submit();
		
	}

	function cambiarOrden(pos,orden) {
		document.frm_listado.pos.value=pos;
		document.frm_listado.orden.value =orden;
		document.frm_listado.accion.value = "cambiarOrden";
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

   
                    <div >
                        <form name="frm_busqueda" id="frm_busqueda" action="<?=$_SERVER['PHP_SELF']?>" method="post">
        			<!-- Parametros menu -->
        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
                    <!--     -->
                        
                        <div class="formbody">
                          Nombre: <input name="fnombre" type="text" style="width:100px" value="<?=$_POST["fnombre"]?>"  />
                          Equipo: <input name="fequipo" type="text" style="width:100px" value="<?=$_POST["fequipo"]?>"  />
                          <input class="submit" value="Buscar" type="submit" style="font-size:11px" />
                          <input class="submit" value="Limpiar" type="button" style="font-size:11px" onclick="javascript:limpiar('frm_busqueda'); document.frm_busqueda.submit();" />
                        </div>
                        </form>
                    </div>


                    <form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
                    <input type="hidden" name="_pag" value="<?=$pag?>" />
                    <input type="hidden" name="id" value="<?=$_POST["id"]?>" />
                    <input type="hidden" name="activo" value="" />
                    <input type="hidden" name="pos" value="" />
                    <input type="hidden" name="orden" value="" />
                    
                    <input type="hidden" name="accion" value="" />
        
        			<!-- Parametros menu -->
        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
                    <!--     -->
                    
        
        
                    <!-- Filtros -->
                    <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
                    <input name="fequipo" type="hidden" style="width:100px" value="<?=$_POST["fequipo"]?>"  />
                    <!-- Fin filtros -->
                    
                 

				<div style="margin-left:20px; float:left" >
				
				<? if ($total != 0) {	?>
				
				<? if ( $pag > 1 ) {?>
					 <a href="javascript: document.frm_listado._pag.value=<?=$pag-1?>;  document.frm_listado.submit();"><img src="images/icono-anterior-on.gif" alt="anterior" title="anterior" /></a>
				<? } else {?>
					 <img src="images/icono-anterior-off.gif" alt="anterior" title="anterior" />
				<? }?>
				
				<? if ( $pag < $total ) {?>						 
					 <a href="javascript: document.frm_listado._pag.value=<?=$pag+1?>;  document.frm_listado.submit();"><img src="images/icono-siguiente-on.gif" alt="siguiente" title="siguiente" /></a>
				<? } else {?>
					 <img src="images/icono-siguiente-off.gif" alt="siguiente" title="siguiente" />
				<? }?>

																 									  
               		<span>P&aacute;gina <? echo $pag; ?> de <? echo $total; ?>
                     	&nbsp;&nbsp;N&uacute;mero de p&aacute;gina 
				    <select style="width:40px"  name="nro_pag" id="nro_pag" onchange="document.frm_listado._pag.value=this.value;  document.frm_listado.submit();" >
				 <?   
 					for($p=1; $p<=$total; $p++) {
				 ?>
				 		<option <? if ($p == $pag) echo "selected"; ?> value="<?=$p?>"><?=$p?></option>
				 <? } ?>
				 </select>
                     
                     </span>
				<? } ?>							  
			</div>
			
			<div align="right" style="margin-right:10px; margin-bottom:10px" >
            	<input class="button" onclick="javascript:nuevo()" type="button" value="Nueva Jugadora" />
            </div>

			<table width="928">
				
				<tr>
					<th >Nombre</th>
					<th width="15%">Equipo</th>                                        
					<th width="15%">Posici&oacute;n</th>                                        
					<th width="10%">Opciones</th>
				</tr>

				<? if (count($datos) == 0) { ?>
				
				
				<tr>
						<td colspan="4" align="center">No existen jugadoras</td>
			  </tr>
				
               
				<? } else { 
				 	$total = count($datos);	
					$tt = $total - 1;
					for ( $i = 0; $i < $total; $i++ ) {
        			
				?>


					<tr>
                     <td align="left"><?=$datos[$i]["nombre"]?></td>
                     <td align="left"><?=$datos[$i]["equipo"]?></td>
                     <td align="left"><?=$datos[$i]["posicion"]?></td>
                      <td nowrap>
                        <a href="javascript:ver(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/find-icon.png" alt="ver" title="ver" width="20px" height="20px" /></a>
                        <a href="javascript:editar(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/icono-editar.gif" alt="editar" title="editar" /></a>
					   <a href="javascript:borrar(<?=$datos[$i]["id"]?>);"><img border="0" src="images/icono-eliminar.gif" alt="eliminar" title="eliminar" /></a>
 					<!--	<a href="javascript:idioma(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/idioma.jpg" alt="idioma" title="idioma"  width="20" height="20"/></a>--></td>
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