<?
include_once "backEnd/admin/include/config.inc.php";
include_once "include/templateEngine.inc.php"; 
include_once "backEnd/model/torneos.php";
include_once "backEnd/model/torneos.zonas.php";
include_once "backEnd/model/posiciones.php";
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

$cruce = array();
$oEquipo = new Equipos();
$equiposTorneo = $oEquipo -> getTorneoCat($_GET['idTorneoZonaActiva']);
$oFixture = new Fixture();
if ($equiposTorneo != NULL) {
	foreach ($equiposTorneo as $equipo1) {
		foreach ($equiposTorneo as $equipo2) {
			$idCruce = $equipo1['id'].$equipo2['id'];
			$resultado = $oFixture -> resultadoPartido($equipo1['id'], $equipo2['id'], $_GET['idTorneoZonaActiva']);
			if ($resultado != NULL) {
				$cruce[$idCruce] = array($equipo1['id'] => $resultado[0]['golesEquipo1'], $equipo2['id'] => $resultado[0]['golesEquipo2']);
			} else {
				$cruce[$idCruce] = array($equipo1['id'] => '', $equipo2['id'] => '');
			}
		}
	}
}
//var_dump($cruce);

$oZonas = new Zonas();
$zona = $oZonas->get($_GET['idZonaActiva']);

$oPosiciones = new Posiciones();
$tabla = $oPosiciones->armarTabla($_GET['idTorneoZonaActiva']);

// Cargo la plantilla
$twig->display('estadisticasPosiciones.html',array("torneosZonas" => $torneosZonas, "idTorneoActivo" => $_GET['idTorneoActivo'], "tabla" => $tabla, "zona" => $zona[0], "cruces" => $cruce, "equipos" => $equiposTorneo));

?>