<? 
include_once "mysql.class.php";

class Expulsados {

	var $id;
	var $nombre;
	var $idTorneo;
	var $idEquipo;
	var $sancion;
	
	function Expulsados($id="") {
		if ($id != "") {
			$expusaldos = $this->get($id);
			$this->id = $goleadores[0]["id"]; 
			$this->nombre = $goleadores[0]["nombre"];
			$this->idTorneo = $goleadores[0]["idTorneo"];
			$this->idEquipo = $goleadores[0]["idEquipo"];
			$this->sancion = $goleadores[0]["sancion"];
		}
	}
	
	function set($valores){
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];
		$this->idTorneo = $valores["idTorneo"];
		$this->idEquipo = $valores["idEquipo"];
		$this->sancion = $valores["sancion"];
	}
	
	function _setById($id) {	
		$aExpulsados = $this->getById($id, ARRAY_A);	
		$this->set($aExpulsados);
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select c.*, e.nombre as equipo, t.nombre as torneo from expulsados c, equipos e, torneos t where c.idEquipo = e.id and c.idTorneo = t.id" ;
		if ($id != "") {
			$query .= " and c.id = '$id' ";
		}
		$query .= " order by c.nombre DESC";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function getByTorneo($idTorneo="") {
		$db = new Db();
		$query = "Select c.*, e.nombre as equipo, t.nombre as torneo from expulsados c, equipos e, torneos t where c.idEquipo = e.id and c.idTorneo = t.id and t.id = '$idTorneo' order by c.nombre ASC";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function agregar() {
		$db = new Db();	
		$query = "insert into expulsados(nombre, idTorneo, idEquipo, sancion) values ('".$this->nombre."','".$this->idTorneo."','".$this->idEquipo."','".$this->sancion."')" ;
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
				  idTorneo  = '". $this->idTorneo."',
				  idEquipo  = '". $this->idEquipo."',
				  sancion  = '". $this->sancion."'
				  where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  e.nombre as equipo, t.nombre as torneo, c.* from expulsados c, equipos e, torneos t where c.idEquipo = e.id and c.idTorneo = t.id ";
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