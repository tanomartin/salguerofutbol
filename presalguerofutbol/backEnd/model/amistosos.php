<?PHP
include_once "mysql.class.php";

class Amistosos {

	var $id;
	var $idEquipo1;
	var $idEquipo2;
	var $fechaPartido;
	var $horaPartido;
    var $suspendido;
    var $idSede;
    var $cancha;	
	
	function Amistosos($id="") {
		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->idFecha = $valores[0]["idFecha"]; 
			$this->idEquipo1 = $valores[0]["idEquipo1"];
			$this->idEquipo2 = $valores[0]["idEquipo2"]; 
			$this->fechaPartido = cambiaf_a_mysql($valores[0]["fechaPartido"]);
			$this->horaPartido = $valores[0]["horaPartido"];
			$this->idSede = $valores[0]["idSede"];
			$this->cancha = $valores[0]["cancha"]; 
		}
	}

	function set($valores){
		$this->id = $valores["id"]; 
		$this->idFecha = $valores["idFecha"]; 
		$this->idEquipo1 = $valores["idEquipo1"];
		$this->idEquipo2 = $valores["idEquipo2"]; 
		$this->fechaPartido = cambiaf_a_mysql($valores["fechaPartido"]);
		$this->horaPartido = $valores["horaPartido"];
		$this->idSede = $valores["idSede"];
		$this->cancha = $valores["cancha"];
	}
	
	function _setById($id) {
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		
	function insertar() {
		$db = new Db();
		$query = "insert into amistosos(
				idEquipo1,idEquipo2,fechaPartido,horaPartido,idSede,cancha
				) values (".
				"'".$this->idEquipo1."',".			
				"'".$this->idEquipo2."',".
				"'".$this->fechaPartido."',".
				"'".$this->horaPartido."',".
				"'".$this->idSede."',".
				"'".$this->cancha."')";
		$this->id = $db->query($query); 
		$db->close();
	}

	function eliminar() {
		$db = new Db();
		$query = "delete from amistosos where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function actualizar() {
		$db = new Db();
		$query = "update amistosos set 	
		          idEquipo1 = '". $this->idEquipo1."',
		          idEquipo2 = '". $this->idEquipo2."',
		          fechaPartido = '". $this->fechaPartido."',
		          horaPartido = '". $this->horaPartido."',
		          idSede = '". $this->idSede."',
		          cancha = '". $this->cancha."'
				  where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select e.*
				  from amistosos e" ;
		if ($id != "") {
			$query .= " where e.id = '$id' ";
		}
		$query .= " order by e.fechaPartido";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  x.*, e1.nombre as equipo1, e2.nombre as equipo2, s.nombre as sede
		          from amistosos x
				  left join  equipos e1 on x.idEquipo1 = e1.id 
				  left join  equipos e2  on x.idEquipo2 = e2.id 
				  left join  sedes s on x.idSede = s.id ";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and e1.nombre like '%".strtoupper($filtros["fnombre"])."%'";		    
		$query.= " order by fechaPartido DESC, horaPartido DESC LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}
	
	function getByIdEquipo($arrayIdEquipo="") {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  x.*, DATE_FORMAT(x.fechaPartido, '%d/%m/%Y') as fechaPartidoFormato, e1.nombre as equipo1, e2.nombre as equipo2, s.nombre as sede
		          from amistosos x
				  left join  equipos e1 on x.idEquipo1 = e1.id 
				  left join  equipos e2  on x.idEquipo2 = e2.id
				  left join  sedes s  on x.idSede = s.id
				  where
				  x.idEquipo1 in ($arrayIdEquipo) || x.idEquipo2 in ($arrayIdEquipo) 
				  order by fechaPartido DESC, horaPartido DESC";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function getByIdEquipoNoJugados($arrayIdEquipo="") {
		$hoy = date('Y-m-d');
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  x.*, DATE_FORMAT(x.fechaPartido, '%d/%m/%Y') as fechaPartidoFormato, e1.nombre as equipo1, e2.nombre as equipo2, s.nombre as sede
		          from amistosos x
				  left join  equipos e1 on x.idEquipo1 = e1.id 
				  left join  equipos e2  on x.idEquipo2 = e2.id
				  left join  sedes s  on x.idSede = s.id
				  where
				 (x.idEquipo1 in ($arrayIdEquipo) || x.idEquipo2 in ($arrayIdEquipo)) and x.fechaPartido >= '$hoy'
				  order by fechaPartido DESC, idsede ASC, horaPartido ASC, cancha ASC";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function getNoJugados() {
		$hoy = date('Y-m-d');
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  x.*, DATE_FORMAT(x.fechaPartido, '%d/%m/%Y') as fechaPartidoFormato, e1.nombre as equipo1, e2.nombre as equipo2, s.nombre as sede
		          from amistosos x
				  left join  equipos e1 on x.idEquipo1 = e1.id 
				  left join  equipos e2  on x.idEquipo2 = e2.id
				  left join  sedes s  on x.idSede = s.id
				  where
				  x.fechaPartido >= '$hoy'
				  order by fechaPartido DESC, idsede ASC, horaPartido ASC, cancha ASC";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function existePrevio($idAmistoso="") {
		$db = new Db();
		$query = "select count(*) as cantidad from amistosos where 
		          fechaPartido = '". $this->fechaPartido."' and
		          horaPartido = '". $this->horaPartido."' and
		          idSede = '". $this->idSede."' and
		          cancha = '". $this->cancha."' and id <> '$idAmistoso'";
		//print($query."<br>");
		$res = $db->getRow($query); 
		$db->close();
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	}
}

?>