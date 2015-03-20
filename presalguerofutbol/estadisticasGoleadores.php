<?
include_once "backEnd/admin/include/config.inc.php";
include_once "include/templateEngine.inc.php";
include_once "backEnd/model/goleadores.php";
include_once "backEnd/model/torneos.php";

$oObj = new Goleadores();
$goleadores = $oObj->get();

$oObj = new Torneos();
$torneos = $oObj->getActivos();

// Cargo la plantilla
$twig->display('estadisticasGoleadores.html',array("goleadores" => $goleadores, "torneos" => $torneos, "idTorneoActivo" => $_GET['idTorneoActivo']));

?>