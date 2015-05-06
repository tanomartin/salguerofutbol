<?
include_once "backEnd/admin/include/config.inc.php";
include_once "include/templateEngine.inc.php"; 
include_once "backEnd/model/torneos.php";
include_once "backEnd/model/torneos.zonas.php";
include_once "backEnd/model/posiciones.php";
include_once "backEnd/model/cruces.php";
include_once "backEnd/model/zonas.php";
include_once "backEnd/model/fixture.php";

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
if (isset($_GET['idTorneoZonaActiva']) && isset($_GET['idZonaActiva'])) {
	$cruce = array();
	$oCruces = new Cruces();
	$cruce = $oCruces->armarCruces($_GET['idTorneoZonaActiva']);
	
	$oEquipo = new Equipos();
	$equiposTorneo = $oEquipo -> getTorneoCat($_GET['idTorneoZonaActiva']);
	
	$oZonas = new Zonas();
	$zona = $oZonas->get($_GET['idZonaActiva']);
	
	$oPosiciones = new Posiciones();
	$tabla = $oPosiciones->armarTabla($_GET['idTorneoZonaActiva']);
}

// Cargo la plantilla
$twig->display('estadisticasPosiciones.html',array("torneosZonas" => $torneosZonas, "idTorneoActivo" => $_GET['idTorneoActivo'], "tabla" => $tabla, "zona" => $zona[0], "cruces" => $cruce, "equipos" => $equiposTorneo));

?>