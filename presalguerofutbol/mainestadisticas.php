<?
include_once "backEnd/admin/include/config.inc.php";
include_once "include/templateEngine.inc.php";
include_once "backEnd/model/torneos.php";
include_once "backEnd/model/torneos.zonas.php";

$oObj = new Torneos();
$datos = $oObj->getActivos();

$oZonas = new TorneoZona();
$zonas = $oZonas->getByTorneo($_GET['idTorneoActivo']);

// Cargo la plantilla
$twig->display('mainestadisticas.html',array("torneos" => $datos, "zonas" => $zonas));

?>