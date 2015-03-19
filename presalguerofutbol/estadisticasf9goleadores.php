<?
include_once "backEnd/admin/include/config.inc.php";
include_once "include/templateEngine.inc.php";
include_once "backEnd/model/goleadores.php";

$oObj = new Goleadores();
$datos = $oObj->get();

// Cargo la plantilla
$twig->display('estadisticasf9goleadores.html',array("goleadores" => $datos));

?>