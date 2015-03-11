<?	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/fixture.php";
	include_once "../model/resultados.php";
	include_once "../model/jugadoras.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$menu = "Gestion";

	//print_r($_POST);

	switch ($_POST["accion"]) {
	

		case "editar":
		
			include("fixture.edit.php");
			exit;
			break;
			
		case "ver":
		
			include("fixture.edit.php");
			exit;
			break;
			
		case "resultado":
		
			include("fixture.resultado.php");
			exit;
			break;
			
		case "guardar":	
		
//			$data =   decodeUTF8($_POST);
			$data =   $_POST;
			$files = $_FILES;

			$oObj = new Fixture();

			$oObj->set($data);
			if($_POST["id"] == "-1") { // Tiene el valor de session_id()
				$oObj->insertar();
			} else {
				$oObj->actualizar($files);
			}
		
			break;


		case "guardarResultado":	
		
//			$data =   decodeUTF8($_POST);

			$oObj = new Resultados();

			$oObj->borrarByIdFixture($_POST['idFixture']);

			$oObj1 = new Jugadoras();

			$aJugadoras = $oObj1->getByEquipo($_POST['idEquipo1']);

			$oObj->idFixture = $_POST['idFixture'];
			
			$golesT = 0;
			$amarillasT = 0;
			$rojasT  = 0;

			for ($i=0;$i<count($aJugadoras);$i++) {
		
				$nombre = $aJugadoras[$i]['id']."_goles";
				$goles = ($_POST[$nombre])?$_POST[$nombre]:0;
				
				$nombre = $aJugadoras[$i]['id']."_amarillas";
				$amarillas = ($_POST[$nombre])?$_POST[$nombre]:0;

				$nombre = $aJugadoras[$i]['id']."_rojas";
				$rojas = ($_POST[$nombre])?$_POST[$nombre]:0;

				$mejor_jugadora = ( $aJugadoras[$i]['id'] == $_POST['mejor_jugadora'])? 'S' : 'N';
				$oObj->idJugadora = $aJugadoras[$i]['id'];
				$oObj->goles = $goles;
				$oObj->tarjeta_amarilla = $amarillas;
				$oObj->tarjeta_roja = $rojas;
				$oObj->mejor_jugadora = $mejor_jugadora;				
			
				$oObj->insertar();

				$golesT += ($goles>0)?$goles:0;
				$amarillasT += ($amarillas>0)?$amarillas:0;
				$rojasT += ($rojas>0)?$rojas:0;

			}
		
		
			$oFix = new Fixture();
			
			$oFix -> modicarCampoValor("golesEquipo1",$golesT,"id",$_POST['idFixture']);
			$oFix -> modicarCampoValor("amonestadosEquipo1",$amarillasT,"id",$_POST['idFixture']);
			$oFix -> modicarCampoValor("expulsadosEquipo1",$rojasT,"id",$_POST['idFixture']);

			$aJugadoras = $oObj1->getByEquipo($_POST['idEquipo2']);
	
			$golesT = 0;
			$amarillasT = 0;
			$rojasT  = 0;

			for ($i=0;$i<count($aJugadoras);$i++) {
				
				$nombre = $aJugadoras[$i]['id']."_goles";
				$goles = ($_POST[$nombre])?$_POST[$nombre]:0;
				
				$nombre = $aJugadoras[$i]['id']."_amarillas";
				$amarillas = ($_POST[$nombre])?$_POST[$nombre]:0;

				$nombre = $aJugadoras[$i]['id']."_rojas";
				$rojas = ($_POST[$nombre])?$_POST[$nombre]:0;
			
				$mejor_jugadora = ( $aJugadoras[$i]['id'] == $_POST['mejor_jugadora'])? 'S' : 'N';

				$oObj->idJugadora = $aJugadoras[$i]['id'];
				$oObj->goles = $goles;
				$oObj->tarjeta_amarilla = $amarillas;
				$oObj->tarjeta_roja = $rojas;
				$oObj->mejor_jugadora = $mejor_jugadora;				

				$oObj->insertar();
		
				$golesT += ($goles>0)?$goles:0;
				$amarillasT += ($amarillas>0)?$amarillas:0;
				$rojasT += ($rojas>0)?$rojas:0;

			}
		
			$oFix = new Fixture();
			
			$oFix -> modicarCampoValor("golesEquipo2",$golesT,"id",$_POST['idFixture']);
			$oFix -> modicarCampoValor("amonestadosEquipo2",$amarillasT,"id",$_POST['idFixture']);
			$oFix -> modicarCampoValor("expulsadosEquipo2",$rojasT,"id",$_POST['idFixture']);

		  break;

		case "borrar":
		
			//$data =   decodeUTF8($_POST);
			$data =   $_POST;
			$oObj = new Fixture();

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
	$oObj = new Fixture();
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
	
	function resultado(id){
		
		document.frm_listado.accion.value = "resultado";
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
            	<input class="button" onclick="javascript:nuevo()" type="button" value="Nuevo Equipo" />
            </div>

			<table width="928">
				
				<tr>
					<th width="15%">Torneo</th>                                        
					<th width="15%">Categor&iacute;a</th>     
					<th width="15%">Equipo 1</th>     
					<th width="15%">Equipo 2</th>                                                            
					<th width="15%">Fecha</th>                                                                                
					<th width="15%">Hora</th>                                                                                                    
					<th width="10%">Opciones</th>
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
                     <td align="left"><?=$datos[$i]["equipo1"]?></td>   
                     <td align="left"><?=$datos[$i]["equipo2"]?></td>                                          
                     <td align="left"><?=   cambiaf_a_normal($datos[$i]["fechaPartido"])?></td>   
                     <td align="left"><?=$datos[$i]["horaPartido"]?></td>                                                                                    
                      <td nowrap>
                        <a href="javascript:ver(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/find-icon.png" alt="ver" title="ver" width="20px" height="20px" /></a>
                        <a href="javascript:editar(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/icono-editar.gif" alt="editar" title="editar" /></a>
					    <a href="javascript:borrar(<?=$datos[$i]["id"]?>);"><img border="0" src="images/icono-eliminar.gif" alt="eliminar" title="eliminar" /></a>
                        <a href="javascript:resultado(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/infoLoc.jpg" alt="Detalle Resultado" title="Detalle Resultado" width="20px" height="20px" /></a>
                       
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