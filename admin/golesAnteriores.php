<?	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/golesAnteriores.php";
	include_once "../model/equipos.php";
	include_once "../model/jugadoras.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$menu = "Gestion";

	//print_r($_POST);

	switch ($_POST["accion"]) {
	

		case "goles":
		
			include("goles.edit.php");
			exit;
			break;
			
		case "guardarResultado":	
		
			$oObj = new GolesAnt();

			$oObj->eliminar($_POST['id_torneo'],$_POST['id_categoria'],$_POST['idEquipo1']);

			$oObj1 = new Jugadoras();

			$aJugadoras = $oObj1->getByEquipo($_POST['idEquipo1']);


			for ($i=0;$i<count($aJugadoras);$i++) {
		
				$nombre = $aJugadoras[$i]['id']."_goles";
				$goles = ($_POST[$nombre])?$_POST[$nombre]:0;
				
				$oObj->id_jugadora = $aJugadoras[$i]['id'];
				$oObj->goles = $goles;
				$oObj->id_torneo = $_POST['id_torneo'];
				$oObj->id_categoria = $_POST['id_categoria'];
				$oObj->id_equipo = $_POST['idEquipo1'];
			
				$oObj->insertar();

			}
		
		

		  break;


	}
	
	

	//Paginacion
	$cant   = 10;
  	$pag    = ($_POST['_pag']>0) ? $_POST['_pag'] : 1;
	$inicio = ($pag - 1) * $cant;
    $fin    = $inicio + $cant;
	// fin Paginacion

	$total = 0;
	$oObj = new Equipos();
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
	
	function goles(id){
		
		document.frm_listado.accion.value = "goles";
		document.frm_listado.id.value = id;
		document.frm_listado.submit();
		
	}
	
	function idioma(id){
		
		document.frm_listado.accion.value = "idioma";
		document.frm_listado.submenu.value = "Idiomas";
		document.frm_listado.id.value = id;
		document.frm_listado.submit();
		
	}
	
	function borrar(id){
		
		document.frm_listado.accion.value = "borrar";
		document.frm_listado.id.value = id;
		document.frm_listado.submit();
		
	}

	function info(id){
		
		document.frm_listado.accion.value = "info";
		document.frm_listado.id.value = id;
		document.frm_listado.submit();
		
	}

	function cambiarActivo(id,activo) {
		document.frm_listado.activo.value=activo;
		document.frm_listado.id.value = id;
		document.frm_listado.accion.value = "cambiarActivo";
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
                          Nombre Equipo: <input name="fnombre" type="text" style="width:100px" value="<?=$_POST["fnombre"]?>"  />
                          Torneo: <input name="ftorneo" type="text" style="width:100px" value="<?=$_POST["ftorneo"]?>"  />
                          Categor&iacute;a: <input name="fcategoria" type="text" style="width:100px" value="<?=$_POST["fcategoria"]?>"  />                          
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
                    <input name="ftorneo" type="hidden" style="width:100px" value="<?=$_POST["ftorneo"]?>"  />
                    <input name="fcategoria" type="hidden" style="width:100px" value="<?=$_POST["fcategoria"]?>"  />                          
                    
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
            	<!--<input class="button" onclick="javascript:nuevo()" type="button" value="Nuevo Equipo" />-->&nbsp;
            </div>

			<table width="928">
				
				<tr>
					<th >Torneo</th>                                        
					<th >Categor&iacute;a</th>     
					<th >Equipo 1</th>     
					<th width="15%"></th>     

				</tr>

				<? if (count($datos) == 0) { ?>
				
				
				<tr>
						<td colspan="7" align="center">No existen fixture</td>
			  </tr>
				
               
				<? } else { 
				 	$total = count($datos);	
					$tt = $total - 1;
					for ( $i = 0; $i < $total; $i++ ) {
        			
				?>


					<tr>
                     <td align="left"><?=$datos[$i]["torneo"]?></td>
                     <td align="left"><?=$datos[$i]["categoria"]?></td>
                     <td align="left"><?=$datos[$i]["nombre"]?></td>   
                      <td nowrap>
                        <a href="javascript:goles(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/infoLoc.jpg" alt="Goles Anteriores" title="Goles Anteriores" width="20px" height="20px" /></a>
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