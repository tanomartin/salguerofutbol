<?
	include_once "include/config.inc.php";
	include_once "../model/torneos.zonas.php";	
	header("Content-Type:text/html; charset=utf-8"); 
	
	$oTorneoZona = new TorneoZona();
	$aTorneoZona = $oTorneoZona->getByTorneoSub($_REQUEST['id']);

?>
	 <select name="<?=$_REQUEST["id_sublista"]?>" id="<?=$_REQUEST["id_sublista"]?>" class="validate-selection"  onChange="clearFecha('idFecha');clearEquipo1('idEquipo1');clearEquipo2('idEquipo2');return listOnChange('idTorneoCat', '', 'fechaList','fecha_data.php','advice2','idFecha','idFecha');" >
<? if($_REQUEST["id"]==-1) { ?>
	<option value="-1">Seleccione antes un Torneo...</option>
<? } else {?>
	<option value="-1">Seleccione una Zona...</option>
<? } ?>    
<?
	for ($i=0;$i<count($aTorneoZona);$i++) 
	{
?>		
		<option value="<?=$aTorneoZona[$i]["id"]?>"><?=$aTorneoZona[$i]["nombreCorto"]?> <? if ( $aTorneoZona[$i]["nombreCat"] != "" ){ echo "- ". $aTorneoZona[$i]["nombreCat"]; } ?></option>
<?	
	}
?>
	</select>
	<span id="<?=$_REQUEST["id_status"]?>"> </span>
