<?PHP
include_once "include/config.inc.php";
include_once "include/_funciones.php";
include_once "mysql.class.php";

class Resultados {

	var $idFixture;
	var $idJugadora;
	var $goles;
	var $tarjeta_amarilla;
	var $tarjeta_roja;
	var $mejor_jugadora;
	
	function Resultados($idFixture="") {

		if ($idFixture != "") {
			$valores = $this->get($idFixture);
			$this->idFixture = $valores[0]["idFixture"]; 
			$this->idJugadora = $valores[0]["idJugadora"];			
			$this->goles = ($valores[0]["goles"])?$valores[0]["goles"]:0;
			$this->tarjeta_amarilla = ($valores[0]["tarjeta_amarilla"])?$valores[0]["tarjeta_amarilla"]:0; 
			$this->tarjeta_roja = ($valores[0]["tarjeta_roja"])?$valores[0]["tarjeta_roja"]:0;
			$this->mejor_jugadora = $valores[0]["mejor_jugadora"];
		}
	}

	
	function set($valores){
		
		$this->idFixture = $valores["idFixture"]; 
		$this->idJugadora = $valores["idJugadora"];			
		$this->goles = ($valores["goles"])?$valores["goles"]:0;
		$this->tarjeta_amarilla = ($valores["tarjeta_amarilla"])?$valores["tarjeta_amarilla"]:0; 
		$this->tarjeta_roja = ($valores["tarjeta_roja"])?$valores["tarjeta_roja"]:0;
   	    $this->mejor_jugadora = $valores["mejor_jugadora"];
		
	}
	
	function _setById($idFixture) {
				
		$aValores = $this->getById($idFixture, ARRAY_A);	
		$this->set($aValores);
	}
		

	function insertar() {
	
		$db = new Db();

		if ( ($this->goles != 0) || ($this->tarjeta_amarilla != 0) || ($this->tarjeta_roja != 0) ){
			$query = "insert into ga_resultados(idFixture,idJugadora,
				goles,tarjeta_amarilla,tarjeta_roja,mejor_jugadora
				) values (".
				"'".$this->idFixture."',".
				"'".$this->idJugadora."',".				
				"'".$this->goles."',".
				"'".$this->tarjeta_amarilla."',".
				"'".$this->tarjeta_roja."',".				
				"'".$this->mejor_jugadora."')";

			$db->query($query); 
		}

		$db->close();

	}


	function borrarByIdFixture($idFixture) {
	
		$db = new Db();
			
		$query = "delete from ga_resultados where idFixture = ".$idFixture ;
	  
		$db->query($query); 

		$db->close();
	
	}
	
	function actualizar() {

		$db = new Db();

		if ( ($this->goles != 0) || ($this->tarjeta_amarilla != 0) || ($this->tarjeta_roja != 0) ){
			
			$query = "update ga_resultados set 
		          idJugadora = '". $this->idJugadora."',		
		          goles = '". $this->goles."',
		          tarjeta_amarilla = '". $this->tarjeta_amarilla."',
		          tarjeta_roja = '". $this->tarjeta_roja."'
				  where idFixture = ".$this->idFixture."
				  and idJugadora = ".$this->idJugadora ;
				  
			$db->query($query); 
		}
		
		$db->close();
	
	}
	
	function get($idFixture="",$idJugadora="") {
	
		$db = new Db();
		
		$query = "Select e.*
				  from ga_resultados e
				  where 1 = 1 " ;
		

		if ($idFixture != "") {
		
			$query .= " and e.idFixture = '$idFixture' ";
		}

		if ($idJugadora != "") {
		
			$query .= " and e.idJugadora = '$idJugadora' ";
		}

		$query .= " order by e.idJugadora";

		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	
	}

	function goleadoras($idTorneoCat) {

		$db = new Db();

		$query = "
		          select r.idJugadora, j.nombre, eq.nombre as nombreEquipo, SUM(r.goles) + IFNULL(a.goles,0) as goles, count(*) as partidos  
		          from ga_resultados r inner join ga_fixture fx on r.idFixture = fx.id 
				  inner join ga_fechas f on fx.idFecha = f.id
				  inner join ga_jugadoras j on r.idJugadora = j.id
				  inner join ga_equipos eq on  j.idEquipo = eq.id
				  inner join ga_torneos_categorias tc on f.idTorneoCat = tc.id
				  left join  ga_goles_anteriores a
				  on tc.id_torneo 	= a.id_torneo  and tc.id_categoria = a.id_categoria  and a.id_jugadora = j.id and a.id_equipo=  j.idEquipo
				  where  f.idTorneoCat =  ".$idTorneoCat." 
				  GROUP BY idJugadora, j.nombre,IFNULL(a.goles,0)
			  
				  order by 4 desc LIMIT 0,15";
			  
		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();

		return $res;
		
	}
	
	
	function getTarjetasByIdJugadora($idJugadora="") {
	
		$db = new Db();
		
			$query = "Select sum(tarjeta_amarilla) amarillas, sum(tarjeta_roja) rojas
				  from ga_resultados r, ga_fixture fx
				  where r.idFixture = fx.id and idJugadora = '$idJugadora' ";
		
		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	}
}

?>