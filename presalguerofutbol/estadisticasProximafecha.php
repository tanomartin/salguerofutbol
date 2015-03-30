<?php
	include_once "backEnd/admin/include/config.inc.php";
	include_once "include/templateEngine.inc.php"; 
	include_once "backEnd/model/fechas.php";
	include_once "backEnd/model/torneos.php";
	include_once "backEnd/model/torneos.zonas.php";
	include_once "backEnd/model/zonas.php";
	include_once "backEnd/model/equipos.php";
	include_once "backEnd/model/fixture.php";
	include_once "backEnd/model/amistosos.php";
	
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
	
	$oFechas = new Fechas();
	$fechas = $oFechas -> getFechaProxima($_GET['idTorneoZonaActiva']);
	
	$oPartidos = new Fixture();
	$partidos = $oPartidos -> getByFecha($fechas[0]['id']);
	
	$oZonas = new Zonas();
	$zona = $oZonas->get($_GET['idZonaActiva']);

	$oEquipos = new Equipos();
	$arryaEquipos = $oEquipos->getTorneoCat($_GET['idTorneoZonaActiva']);
	$index = 0;
	if ($arryaEquipos != NULL) {
		foreach($arryaEquipos as $equipo) {
			$whereIdIn .= $equipo['id'].",";
		}
	}
	$whereIdIn = substr($whereIdIn,0,-1);

	$oAmistosos = new Amistosos();
	$amistosos = $oAmistosos -> getByIdEquipoNoJugados($whereIdIn);

	// Cargo la plantilla
	$twig->display('estadisticasProximafecha.html',array("torneosZonas" => $torneosZonas, "idTorneoActivo" => $_GET['idTorneoActivo'], "partidos" => $partidos, "zona" => $zona[0], "fecha" => $fechas[0], "amistosos" => $amistosos));

?>