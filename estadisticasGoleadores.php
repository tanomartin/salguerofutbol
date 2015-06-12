<?
include_once "backEnd/admin/include/config.inc.php";
include_once "include/templateEngine.inc.php";
include_once "backEnd/model/goleadores.php";
include_once "backEnd/model/torneos.php";
include_once "backEnd/model/torneos.zonas.php";

$oObj = new Goleadores();
$goleadores = $oObj->getByTorneo($_GET['idTorneoActivo']);

$torneosZonas = array();
$oObj = new Torneos();
$torneos = $oObj->getActivos();
if ($torneos != NULL) {
	foreach ($torneos as $torneo) {
		$oZonas = new TorneoZona();
		$zonas = $oZonas->getByTorneo($torneo["id"]);
		$torneosZonas[$torneo["id"]] = array("torneo" => $torneo, "zonas" => $zonas);
	}
}

$oTorneo = new Torneos();
$torneo = $oTorneo->get($_GET['idTorneoActivo']);

// Cargo la plantilla
$twig->display('estadisticasGoleadores.html',array("torneosZonas" => $torneosZonas, "goleadores" => $goleadores, "idTorneoActivo" => $_GET['idTorneoActivo'], "torneo" => $torneo[0]));

?>