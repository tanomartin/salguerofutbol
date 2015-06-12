<?PHP
include_once "mysql.class.php";
include_once "fixture.php";
include_once "equipos.php";

class Cruces {

	function Cruces($id="") {}

	function armarCruces($idTorneoCat)	{	
		$oObj1 = new Fixture();
		$aFixture = $oObj1->getByidTorneoCat($idTorneoCat);
		$valorGanado = 3;
		$valorEmpatado = 1;
		$aCruces = array();

		for ($i=0;$i<count($aFixture);$i++){
			$idCruce = $aFixture[$i]['idEquipo1'].$aFixture[$i]['idEquipo2'];
			$aCruces[$idCruce] = array($aFixture[$i]['idEquipo1'] => $aFixture[$i]['golesEquipo1'], $aFixture[$i]['idEquipo2'] => $aFixture[$i]['golesEquipo2']);
		} 
		return $aCruces;
	}
}

?>