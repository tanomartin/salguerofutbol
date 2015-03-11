<?	include_once "include/config.inc.php";
	include_once "../model/fechas.php";
	include_once "../model/correos.php";
	include_once "include/control_session.php";
	
	$menu = "Secciones";
	$idFecha = $_POST['id'];
	$oFecha = new Fechas();
	$fecha = $oFecha -> get($idFecha);
	$asunto = "Recordatorio Reserva Horario: ".$fecha[0]['nombre']." - ".$fecha[0]['torneo']." - ".$fecha[0]['categoria'];
	$cuerpo = $_POST['cuerpocorreo'];
	$equiposMail = unserialize(urldecode($_POST['equiposMail']));
	
	foreach($equiposMail as $equipo) {
		$idEquipo = $equipo['id_equipo'];
		$email = $equipo['email'];
		$seenvioanterior = $equipo['seenvio'];
		$nombre = $equipo['nombre'];
		if (($email != "") && (!$seenvioanterior)) {
			$valores = array('correo' => $email, 'cuerpo' => $cuerpo, 'equipoId' => $idEquipo, 'equipoNombre' => $nombre, 'asunto' => $asunto);
			$emailOb = new Correos($valores);
			$seEnvio = $emailOb->enviar();
			if ($seEnvio) {
				$emailOb -> cargarCorreo($idEquipo, $idFecha, 'r');
			}
		}
	}

?>
