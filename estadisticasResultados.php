<?php
	include_once "backEnd/admin/include/config.inc.php";
	include_once "include/templateEngine.inc.php"; 
	include_once "backEnd/model/torneos.php";
	include_once "backEnd/model/torneos.zonas.php";
	include_once "backEnd/model/zonas.php";
	include_once "backEnd/model/equipos.php";
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
	
	$oZonas = new Zonas();
	$zona = $oZonas->get($_GET['idZonaActiva']);

	$oEquipos = new Equipos();
	$equipo = $oEquipos->get($_GET['idEquipo']);

	$oFixture = new Fixture();
	$partidos = $oFixture -> getByEquipo( $_GET['idTorneoZonaActiva'], $_GET['idEquipo']);


	// Cargo la plantilla
	$twig->display('estadisticasResultados.html',array("torneosZonas" => $torneosZonas, "idTorneoZonaActivo" => $_GET['idTorneoZonaActiva'], "idTorneoActivo" => $_GET['idTorneoActivo'], "equipo" => $equipo[0], "zona" => $zona[0], "partidos" => $partidos));

?>