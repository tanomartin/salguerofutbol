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
			$this->suspendido = ($valores[0]["suspendido"]=='on')?1:0; 
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
		$this->suspendido = ($valores["suspendido"]=='on')?1:0; 
	}
	
	function _setById($id) {
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		
	function insertar() {
		$db = new Db();
		$query = "insert into amistosos(
				idEquipo1,idEquipo2,fechaPartido,horaPartido,idSede,cancha,golesEquipo1,golesEquipo2,suspendido
				) values (".
				"'".$this->idEquipo1."',".			
				"'".$this->idEquipo2."',".
				"'".$this->fechaPartido."',".
				"'".$this->horaPartido."',".
				"'".$this->idSede."',".
				"'".$this->cancha."',".				
				"'".$this->golesEquipo1."',".
				"'".$this->golesEquipo2."',".
				"'".$this->suspendido."')";
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
		          cancha = '". $this->cancha."',
		          golesEquipo1 = '". $this->golesEquipo1."',
		          golesEquipo2 = '". $this->golesEquipo2."',
		          suspendido = '". $this->suspendido."'
				  where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select e.*
				  from amistosos e" ;
		if ($id != "") {
			$query .= " and e.id = '$id' ";
		}
		$query .= " order by e.fechaPartido";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  x.*, e1.nombre as equipo1, e2.nombre as equipo2
		          from amistosos x
				  left join  equipos e1 on x.idEquipo1 = e1.id 
				  left join  equipos e2  on x.idEquipo2 = e2.id 
				  where 1 = 1 ";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and e1.nombre like '%".strtoupper($filtros["fnombre"])."%'";		    
		$query.= " order by fechaPartido DESC, horaPartido DESC LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}
}

?>