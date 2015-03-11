<?	include_once "include/config.inc.php";

	include_once "../model/parametros.php";

//print_r($_POST);

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	switch ($_POST["accion"]) {
	

		case "editar":
		
			include("parametros.edit.php");
			exit;
			break;

			
		case "guardar":	
		
//			$data =   decodeUTF8($_POST);
			$data =   $_POST;
			$oObj = new Parametro();

			$oObj->set($data);
	
			$oObj->modificarValor();

			break;


	}
	

	$oObj = new Parametro();

	//Paginacion
	$cant   = 10;
  	$pag    = ($_POST['_pag']>0) ? $_POST['_pag'] : 1;
	$inicio = ($pag - 1) * $cant;
    $fin    = $inicio + $cant;
	// fin Paginacion

	$total = 0;
	$datos = $oObj->getPaginado($_REQUEST, $inicio, $cant, $total);

	$esultimo = (count($datos) == 1)? "S" : "N" ;
	?>
    
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>

<!-- base href="http://www.typolight.org/" -->
<title>Panel de Control</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Panel de Control.">
<meta name="keywords" content="">
<meta name="robots" content="index,follow">

<? include("encabezado.php"); ?>


<script language="javascript">

	function nuevo(){

		document.frm_listado.accion.value = "editar";
		document.frm_listado.id.value = -1;
		document.frm_listado.submit();
		
	}

	function editar(id){
		
		document.frm_listado.accion.value = "editar";
		document.frm_listado.id.value = id;
		document.frm_listado.submit();
		
	}

	function borrar(id){
		if(confirm("Desea Eliminarlo?")) {
			document.frm_listado.accion.value = "borrar";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
		}
	}

	
	function ordenar(orden, dir) {
   
   		document.frm_listado.orden.value = orden;
   		document.frm_listado.dir.value = dir;
   		document.frm_listado.submit();
 	}
	
	function reordenar(id, dir){
		
		document.frm_listado.accion.value = "reordenar";
		document.frm_listado.id.value = id;
		document.frm_listado.dirr.value = dir;
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
                    <input type="hidden" name="_pag" value="<?=$pag?>" />
                    <input type="hidden" name="_pag_gal" value="<?=$pag?>" />
                    <input type="hidden" name="id" value="<?=$_POST["id"]?>" />
                    <input type="hidden" name="accion" value="" />
                     <input type="hidden" name="dirr" value="<?=$_POST["dirr"]?>" />
                
                    <!-- Parametros menu -->
                    <input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
                    <!--     -->                            
        
                    <!-- Filtros -->
                    <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
                    <!-- Fin filtros -->
                 
                    <!-- orden -->
                    <input type="hidden" name="orden" value="<?=$orden?>" />
                    <input type="hidden" name="dir" value="<?=$dir?>" />
                    <!-- orden -->
                    

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
                
                &nbsp;&nbsp; </div>

			<div align="right" style="margin-right:10px; margin-bottom:10px" >
            	&nbsp;
            </div>


			<table width="928">
				    
				<tr>
                    
					<th width="40%">Par&aacute;metro</th>
                    <th width="50%">Valor</th>
					<th width="10%">Opciones</th>
				</tr>


		<? if (count($datos) == 0) { ?>
				
				
				<tr>
						<td colspan="3" align="center">No existen Par&aacute;metros</td>
			  </tr>
				
               
				<? } else { 
				 
				 
					for ( $i = 0; $i < count($datos); $i++ ) {
						
        			
				?>


					<tr style="vertical-align:middle" >

                        <td align="left"><?=$datos[$i]["nombre"]?></td>
                        <td align="left"><?=$datos[$i]["valor"]?></td>
 						
                      <td nowrap  align="center">

                        <a href="javascript:editar(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/icono-editar.gif" alt="editar" title="editar" /></a>
	
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

</div>
</body>

</html>
