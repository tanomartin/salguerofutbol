<?
include_once "backEnd/admin/include/config.inc.php";
include_once "include/templateEngine.inc.php"; 
include_once "backEnd/model/torneos.php";
include_once "backEnd/model/torneos.zonas.php";
include_once "backEnd/model/posiciones.php";
include_once "backEnd/model/zonas.php";

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

$oZonas = new Zonas();
$zona = $oZonas->get($_GET['idZonaActiva']);

$oPosiciones = new Posiciones();
$tabla = $oPosiciones->armarTabla($_GET['idZonaActiva']);

// Cargo la plantilla
$twig->display('estadisticasPosiciones.html',array("torneosZonas" => $torneosZonas, "idTorneoActivo" => $_GET['idTorneoActivo'], "tabla" => $tabla, "zona" => $zona[0]));

?>