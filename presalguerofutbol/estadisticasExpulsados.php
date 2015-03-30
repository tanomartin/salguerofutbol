<?
include_once "backEnd/admin/include/config.inc.php";
include_once "include/templateEngine.inc.php";
include_once "backEnd/model/expulsados.php";
include_once "backEnd/model/torneos.php";
include_once "backEnd/model/torneos.zonas.php";

$oObj = new Expulsados();
$expulsados = $oObj->getByTorneo($_GET['idTorneoActivo']);

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
$twig->display('estadisticasExpulsados.html',array("torneosZonas" => $torneosZonas, "expulsados" => $expulsados, "idTorneoActivo" => $_GET['idTorneoActivo'], "torneo" => $torneo[0]));

?>