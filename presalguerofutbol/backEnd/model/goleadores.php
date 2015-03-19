<? 
include_once "mysql.class.php";

class Goleadores {

	var $id;
	var $nombre;
	var $idEquipo;
	var $goles;
	
	function Goleadores($id="") {
		if ($id != "") {
			$goleadores = $this->get($id);
			$this->id = $goleadores[0]["id"]; 
			$this->nombre = $goleadores[0]["nombre"];
			$this->idEquipo = $goleadores[0]["idEquipo"];
			$this->goles = $goleadores[0]["goles"];
		}
	}
	
	function set($valores){
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];
		$this->idEquipo = $valores["idEquipo"];
		$this->goles = $valores["goles"];
	}
	
	function _setById($id) {	
		$aGoleadores = $this->getById($id, ARRAY_A);	
		$this->set($aGoleadores);
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select c.* from goleadores c where 1=1 " ;
		if ($id != "") {
			$query .= " and c.id = '$id' ";
		}
		$query .= " order by goles DESC";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function agregar() {
		$db = new Db();	
		$query = "insert into goleadores(nombre, idEquipo, goles) values ('".$this->nombre."','".$this->idEquipo."','".$this->goles."')" ;
		$db->query($query); 
		$db->close();	
	}
	
	function eliminar() {
		$db = new Db();
		$query = "delete from goleadores where id = ".$this->id ;	  
		$db->query($query); 
		$db->close();
	}
	
	function modificar() {
		$db = new Db();
		$query = "update goleadores set 
		          nombre  = '". $this->nombre."',
				  idEquipo  = '". $this->idEquipo."',
				  goles  = '". $this->goles."'
				  where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  e.nombre as equipo, c.* from goleadores c, equipos e where c.idEquipo = e.id ";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and c.nombre like '%".strtoupper($_REQUEST["fnombre"])."%'";		  
		$query.= "ORDER BY goles DESC LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}
	
}

?>