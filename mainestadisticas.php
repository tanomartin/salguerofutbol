<?
include_once "backEnd/admin/include/config.inc.php";
include_once "include/templateEngine.inc.php";
include_once "backEnd/model/torneos.php";
include_once "backEnd/model/torneos.zonas.php";

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

// Cargo la plantilla
$twig->display('mainestadisticas.html',array("torneosZonas" => $torneosZonas));

?>