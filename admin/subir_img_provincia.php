<?php
	include_once "include/config.inc.php";
	include_once "../model/fotosProvincia.php";


function subirfichero($nombre,$directorio)
{
      $ruta_fichero = "../$directorio/";
      if (!is_dir("$ruta_fichero"))                                                   
	  	mkdir("$ruta_fichero",0777);
 
      $ruta_relativa ="$directorio/";
 
      $name=$_FILES[$nombre]['name'];
 

 
      $name = ereg_replace("[^a-z0-9._]", "",str_replace(" ", "_", str_replace("%20", "_", strtolower($name))));
 

      $location = $ruta_fichero.$name;
 
      copy($_FILES[$nombre]['tmp_name'],$location);


	$original = @imagecreatefromjpeg($_FILES[$nombre]['tmp_name']);
	$thumb = imagecreatetruecolor(150,150); // Lo haremos de un tama�o 150x150

	//Ahora necesitamos saber de que tama�o es la imagen original:

	$ancho = @imagesx($original);
	$alto = @imagesy($original);

	//A continuaci�n vamos a copiar la imagen original en la imagen en miniatura:

	@imagecopyresampled($thumb,$original,0,0,0,0,150,150,$ancho,$alto);
	$ruta_2 = $ruta_fichero.'thumbs/';

	//Por �ltimo, guardamos la imagen en disco:
	if (!is_dir("$ruta_2"))                                                   
	  	mkdir("$ruta_2",0777);

	$location_2 = $ruta_2.$name;

	imagejpeg($thumb,$location_2,90); // 90 es la calidad de compresi�n	/
      
	  unlink($_FILES[$nombre]['tmp_name']);

      $fichero = $ruta_relativa.$name;

	  return($name);


}
?>

<?php

//include("../db1.php"); 
?>


<?php
	
	switch($_REQUEST["action"])
	{

		case "remove_item":
		{
			RemoveItem($_REQUEST["file"],$_REQUEST["id"]);

			ShowCart();
			break;
		}
		
		default:
		{
			ShowCart();
		}
	}

	
	function RemoveItem($file, $id )
	{
		// Uses an SQL delete statement to remove an item from
		// the users cart
		//include("db1.php");
		unlink($file);
		
		$oFotos = new FotosProvincia($id);
		$oFotos->eliminar();
	}
	




	function ShowCart()
	{

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<body>

<?         
// Actualiza una foto, si aprieto boton guardar
if(isset($_POST[guardar]))
 
{
		
		if ($_FILES["mifichero"]["name"] != "") 
	
      		$v_foto = subirfichero("mifichero","subidos/");
	  	
		else
		
			$v_foto = $_POST["foto"];
		

	  	$oFoto = new FotosProvincia();
		$oFoto-> setValores($_POST["id"], $_POST["id_provincia"], $v_foto, $_POST["video"]);
		$oFoto->actualizar();
	  

 
} 

// Inserta una foto
if(isset($_POST[boton]))
 
{     $foto = subirfichero("mifichero","images/provincias/".$_POST["id_provincia"]);
	  if ($foto) {
	  
	  	$oFoto = new FotosProvincia();
		$oFoto-> setValores("", $_POST["id_provincia"], $foto, $_POST["video"]);
		if( $_POST["video"] == 'N'){
			$oFoto->video = $_POST["video"];
			$oFoto->id_provincia = $_POST["id_provincia"];	
			if( count($oFoto-> getFotos()) <$_POST['maxFotos'])
				$oFoto->insertar();
	  } else {
				$oFoto->insertar();	  
	  }
	  
	  }
} 

?>
 
<? 
   // Muestra el formulario de editar una foto
	if ($_REQUEST["action"] == "edit_item") {
	
		$oFotos = new FotosAnuncio($_REQUEST["id"]);

?>

	<form action="<?=$_SERVER[PHP_SELF]?>" method="post" enctype=multipart/form-data>
	
	<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">
	<input type="hidden" name="foto" value="<?=$oFotos->foto?>">
	<input type="hidden" name="id_provincia" value="<?=$_REQUEST["id_provincia"]?>">
	<input type="hidden" name="video" value="<?=$_REQUEST["video"]?>">
	<div>
	<div align="left" style="float:left">
	<label>Im&aacute;gen: </label>
	<input type=file name=mifichero>
	</div>
	
	<?
	
		$ruta_fichero = "../images/provincias/".$_REQUEST["id_provincia"]."/";
	?>
	
	<div style="float: right">
	
	<label>Im&aacute;gen Actual: </label>
	
	<a href="<?=$ruta_fichero.$oFotos->foto?>" target="_blank" >
	<IMG SRC="thumb/phpThumb.php?src=../<?=$ruta_fichero.$oFotos->foto?>&w=80" WIDTH="80" HEIGHT="80" BORDER="0" HSPACE="8" VSPACE="8"  bgcolor="#FFFFFF"></a>
	
	</div>
	
	</div>
	<br>
<br>
<br>
<br>
<br>
<br>
<br>
	<br><br>
	
	<input class="button" type="submit" name=guardar value="Guardar">
	
	<input class="button" type="button" name=volver value="Volver" onClick="javascript:document.location.href='subir_img_provincia.php?id_provincia=<?=$_REQUEST["id_provincia"]?>&video=<?=$_REQUEST["video"]?>'">
	
	</form>
	
<?	
	} else { 
	
	//Muestra para subir una imagen
?>	 
    <div align="left"> 

	<form action="<?=$_SERVER[PHP_SELF]?>" method="post" enctype=multipart/form-data>
	<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">
	<input type="hidden" name="id_provincia" value="<?=$_REQUEST["id_provincia"]?>">
	<input type="hidden" name="video" value="<?=$_REQUEST["video"]?>">
   	<input type="hidden" name="maxFotos" value="<?=$_REQUEST["maxFotos"]?>">
	<label>Im&aacute;gen: </label>
	<input type=file name=mifichero>
	
	<br><br>
	<input class="button" type="submit" name=boton value="Subir">

<div align="center">
	   <label>Imágenes Subidas</label>
 </div>

 <hr noshade="noshade" >

<table>
<tr>
	<th width="70%">Foto</th>
	<th width="10%"></th>
</tr>

<?		
		// Fotos subidas ya guardadas
		
        $ruta_fichero = "../images/provincias/".$_REQUEST["id_provincia"]."/";
	
		$oProd = new FotosProvincia();
		
		$oProd -> id_provincia = $_REQUEST["id_provincia"];
		$oProd -> video = $_REQUEST["video"];

		$aFotos = $oProd->getFotos();

		?>
  		<tr>
        <td>

  	     <?php for($i=0; $i<count($aFotos); $i++) {
				if (is_file($ruta_fichero.$aFotos[$i]["foto"])) {
	        ?>
	    	 <a href="<?=$ruta_fichero.$aFotos[$i]["foto"]?>" target="_blank" >
	<IMG SRC="thumb/phpThumb.php?src=../<?=$ruta_fichero.$aFotos[$i]["foto"]?>&w=80" WIDTH="80" HEIGHT="80" BORDER="0" HSPACE="8" VSPACE="8"  bgcolor="#FFFFFF"></a>	
    <a href="subir_img_provincia.php?action=remove_item&id=<?=$aFotos[$i]["id"]?>&id_provincia=<?=$_REQUEST["id_provincia"]?>&video=<?=$_REQUEST["video"]?>&file=<?=$ruta_fichero.$aFotos[$i]["foto"]?>&maxFotos=<?=$_REQUEST["maxFotos"]?>"><img src="images/icono-eliminar.gif" width="20" height="20" border="0"></a>
	
<? }
	}
?></td>
	</tr>
</table>

 <hr noshade="noshade">

</form>
</div>
<? } ?>

</body>
</html>

<?php
	}

?>        
 
