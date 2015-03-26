<? 
include_once "mysql.class.php";

class Expulsados {

	var $id;
	var $nombre;
	var $idEquipo;
	var $sancion;
	
	function Expulsados($id="") {
		if ($id != "") {
			$expusaldos = $this->get($id);
			$this->id = $goleadores[0]["id"]; 
			$this->nombre = $goleadores[0]["nombre"];
			$this->idEquipo = $goleadores[0]["idEquipo"];
			$this->sancion = $goleadores[0]["sancion"];
		}
	}
	
	function set($valores){
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];
		$this->idEquipo = $valores["idEquipo"];
		$this->sancion = $valores["sancion"];
	}
	
	function _setById($id) {	
		$aExpulsados = $this->getById($id, ARRAY_A);	
		$this->set($aExpulsados);
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select c.*, e.nombre as equipo from expulsados c, equipos e where c.idEquipo = e.id" ;
		if ($id != "") {
			$query .= " and c.id = '$id' ";
		}
		$query .= " order by c.nombre DESC";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function agregar() {
		$db = new Db();	
		$query = "insert into expulsados(nombre, idEquipo, sancion) values ('".$this->nombre."','".$this->idEquipo."','".$this->sancion."')" ;
		$db->query($query); 
		$db->close();
	}
	
	function eliminar() {
		$db = new Db();
		$query = "delete from expulsados where id = ".$this->id ;	  
		$db->query($query); 
		$db->close();
	}
	
	function modificar() {
		$db = new Db();
		$query = "update expulsados set 
		          nombre  = '". $this->nombre."',
				  idEquipo  = '". $this->idEquipo."',
				  sancion  = '". $this->sancion."'
				  where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  e.nombre as equipo, c.* from expulsados c, equipos e where c.idEquipo = e.id ";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and c.nombre like '%".strtoupper($_REQUEST["fnombre"])."%'";		  
		$query.= "ORDER BY c.nombre DESC LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}
	
}

?>