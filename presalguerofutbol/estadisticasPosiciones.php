<?
include_once "backEnd/admin/include/config.inc.php";
include_once "include/templateEngine.inc.php"; 
include_once "backEnd/model/torneos.php";
include_once "backEnd/model/torneos.zonas.php";
include_once "backEnd/model/posiciones.php";

$oTorneos = new Torneos($_GET['idTorneoActivo']);
$torneos = $oTorneos->getActivos();

$oZonas = new TorneoZona();
$zonas = $oZonas->getByTorneo($_GET['idTorneoActivo']);

$oPosiciones = new Posiciones();
$tablas = array();
if ($zonas != NULL) {
	foreach($zonas as $zona) {
		$idZona = $zona["id"];
		$aTabla = $oPosiciones->armarTabla($idZona);
		$tablas[$idZona] = array("zona" => $zona, "tabla" => $aTabla);
	}
}
	
// Cargo la plantilla
$twig->display('estadisticasPosiciones.html',array("torneos" => $torneos, "idTorneoActivo" => $_GET['idTorneoActivo'], "posiciones" => $tablas));

?>