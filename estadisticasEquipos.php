<?php
	include_once "backEnd/admin/include/config.inc.php";
	include_once "include/templateEngine.inc.php"; 
	include_once "backEnd/model/torneos.php";
	include_once "backEnd/model/torneos.zonas.php";
	include_once "backEnd/model/zonas.php";
	include_once "backEnd/model/equipos.php";
	
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
		$oZonas = new Zonas();
		$zona = $oZonas->get($_GET['idZonaActiva']);
	
		$oEquipos = new Equipos();
		$equipos = $oEquipos->getTorneoCat($_GET['idTorneoZonaActiva']);
	}

	// Cargo la plantilla
	$twig->display('estadisticasEquipos.html',array("torneosZonas" => $torneosZonas, "idTorneoZonaActivo" => $_GET['idTorneoZonaActiva'], "idTorneoActivo" => $_GET['idTorneoActivo'], "equipos" => $equipos, "zona" => $zona[0]));

?>