<?
	include_once "include/config.inc.php";
	include_once "../model/torneos.zonas.php";	
	
	header("Content-Type:text/html; charset=utf-8"); 

	$oTorneoZona = new TorneoZona();
	$aTorneoZona = $oTorneoZona->getByTorneoFechas($_REQUEST['id']);

	if ($_REQUEST['id']!= 0) {
?>

     <select name="<?=$_REQUEST["id_sublista"]?>" id="<?=$_REQUEST["id_sublista"]?>" class="validate-selection" >				
    <? if($_REQUEST["id"]==-1) { ?>
        <option value="-1">Seleccione antes un Torneo...</option>
    <? } else {?>
        <option value="-1">Seleccione una Zona...</option>
    <? } ?>    
    <?
        for ($i=0;$i<count($aTorneoZona);$i++) 
        {
    ?>		
            <option value="<?=$aTorneoZona[$i]["id"]?>"><?=$aTorneoZona[$i]["nombreLargo"]?>  <? if ( $aTorneoZona[$i]["nombreCat"] != "" ){ echo "- ". $aTorneoZona[$i]["nombreCat"]; } ?></option>
    <?	
        }
    ?>
        </select>
<? } else { ?>

     <select name="<?=$_REQUEST["id_sublista"]?>" id="<?=$_REQUEST["id_sublista"]?>">				
        <option value="0">Home</option>
    </select>

<? } ?>

	<span id="<?=$_REQUEST["id_status"]?>"> </span>
