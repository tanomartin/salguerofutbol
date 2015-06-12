<?PHP
include_once "mysql.class.php";
include_once "fixture.php";
include_once "equipos.php";

class Posiciones {

	function Posiciones($id="") {}

	function armarTabla($idTorneoCat)	{	
		$oObj1 = new Fixture();
		$aFixture = $oObj1->getByidTorneoCat($idTorneoCat);
		$oObj2 = new Equipos();
		$aEquipos = $oObj2->getByCategoria($idTorneoCat);
		//$oObj3 = new Parametro();
		$valorGanado = 3;
		$valorEmpatado = 1;
		$aTabla = array();
	
		if ($aEquipos != NULL) {
			$gano1=0;$perdio1=0;$empato1=0;
			$gano2=0;$perdio2=0;$empato2=0;			
			foreach($aEquipos as $key => $valor) {
				$aEquipoR[$valor['id']]['id'] = $valor['id'];
				$aEquipoR[$valor['id']]['nombre'] = $valor['nombre'];
				$aEquipoR[$valor['id']]['par_ganados'] = 0;
				$aEquipoR[$valor['id']]['par_perdidos'] = 0;
				$aEquipoR[$valor['id']]['par_empatados'] = 0;
				$aEquipoR[$valor['id']]['puntaje'] = -$valor['descuento_puntos'];
				$aEquipoR[$valor['id']]['goles_favor'] = 0;
				$aEquipoR[$valor['id']]['goles_contra'] = 0;
				$aEquipoR[$valor['id']]['descripcion'] = $valor['descripcion'];
			}
			for ($i=0;$i<count($aFixture);$i++){
				$aEquipoR[$aFixture[$i][idEquipo1]]['goles_favor'] +=$aFixture[$i][golesEquipo1];
				$aEquipoR[$aFixture[$i][idEquipo1]]['goles_contra'] +=$aFixture[$i][golesEquipo2];
	
				$aEquipoR[$aFixture[$i][idEquipo2]]['goles_favor'] +=$aFixture[$i][golesEquipo2];
				$aEquipoR[$aFixture[$i][idEquipo2]]['goles_contra'] +=$aFixture[$i][golesEquipo1];
				
				$gano1=0;$perdio1=0;$empato1=0;
				$gano2=0;$perdio2=0;$empato2=0;			
			
				if ($aFixture[$i][golesEquipo1]>$aFixture[$i][golesEquipo2]) {
					$gano1=1;$perdio2=1;	
				} else {
					if ($aFixture[$i][golesEquipo1]<$aFixture[$i][golesEquipo2]) {
						$gano2=1;$perdio1=1;	
					} else {
						$empato1=1;$empato2=1;	
					}
			  	}
				$aEquipoR[$aFixture[$i][idEquipo1]]['par_ganados']  += $gano1;
				$aEquipoR[$aFixture[$i][idEquipo1]]['par_perdidos']  += $perdio1;
				$aEquipoR[$aFixture[$i][idEquipo1]]['par_empatados'] += $empato1;
				$aEquipoR[$aFixture[$i][idEquipo1]]['puntaje'] += ($gano1*$valorGanado)+($empato1*$valorEmpatado);
				$aEquipoR[$aFixture[$i][idEquipo2]]['par_ganados']  += $gano2;
				$aEquipoR[$aFixture[$i][idEquipo2]]['par_perdidos']  += $perdio2;
				$aEquipoR[$aFixture[$i][idEquipo2]]['par_empatados'] += $empato2;
				$aEquipoR[$aFixture[$i][idEquipo2]]['puntaje'] += ($gano2*$valorGanado)+($empato2*$valorEmpatado);
			}
			foreach ($aEquipoR as $key => $row){
				if (isset($row['nombre'])){ 
					$puntaje = (($row['puntaje'])?$row['puntaje']*1000:0) + ($row['goles_favor'] - $row['goles_contra']);
					$puntajes[$key]  = $puntaje ;
				}
			}	
			arsort($puntajes);
			foreach ($puntajes as $key => $row) {
				$aTabla[]=	$aEquipoR[$key];
			}
		} 
		return $aTabla;
	}
}

?>