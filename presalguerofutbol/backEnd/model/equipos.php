<?PHP
include_once "include/_funciones.php";
include_once "mysql.class.php";

class Equipos {

	var $id;
	var $nombre;
	var $idTorneoCat;
	var $descripcion;
	var $email;
		
	function Equipos($id="") {
		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->nombre = $valores[0]["nombre"];			
			$this->idTorneoCat = $valores[0]["idTorneoCat"];
		}
	}

	
	function set($valores){
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];			
		$this->idTorneoCat = $valores["idTorneoCat"];
	}
	
	function _setById($id) {
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}

	function insertar() {
		$db = new Db();
		$query = "insert into equipos(idTorneoZona,nombre) values (".
				"'".$this->idTorneoCat."',".
				"'".$this->nombre."')";	  
		$db->query($query); 
		$db->close();
	}

	function eliminar() {
		$db = new Db();
		$query = "delete from equipos where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function actualizar() {
		$db = new Db();
		$query = "update equipos set 
		          nombre = '". $this->nombre."',		
		          idTorneoZona = '". $this->idTorneoCat."'
				  where id = ".$this->id ; 
		$db->query($query); 
		$db->close();
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select e.*, tz.id_torneo, tz.id_zona, tz.id as idTorneoZona, t.nombre as torneo, z.nombreCorto as zona
				  from equipos e, torneos t, torneos_zonas tz, zonas z
				  where e.idTorneoZona = tz.id and tz.id_torneo = t.id and tz.id_zona = z.id " ;
		if ($id != "") {
			$query .= " and e.id = '$id' ";
		}
		$query .= " order by e.nombre";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getByCategoria($id="") {
		$db = new Db();
		$query = "Select e.*
				  from equipos e
				  where e.idTorneoZona = ". $id;
		$query .= " order by e.nombre";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}


	function getPaginado($filtros, $inicio, $cant, &$total) {
		$orden = ($filtros["filter_order"])?$filtros["filter_order"]:"e.id";
		$dir = ($filtros["filter_order_Dir"])?$filtros["filter_order_Dir"]:"asc"; 
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  e.*, t.nombre as torneo, z.nombreCorto as zona
		          from equipos e, torneos t, torneos_zonas tz, zonas z
				  where e.idTorneoZona = tz.id and tz.id_torneo = t.id and tz.id_zona = z.id ";
		if (trim($filtros["fnombre"]) != "")		
			$query.= " and e.nombre like '%".strtoupper($filtros["fnombre"])."%'";		  
		if (trim($filtros["ftorneo"]) != "")		 
			$query.= " and  t.nombre  like '%".strtoupper($filtros["ftorneo"])."%'";		  
		if (trim($filtros["fcategoria"]) != "")		 
			$query.= " and  c.nombreLargo like '%".strtoupper($filtros["fcategoria"])."%'";		  
		$query.= " order by  $orden $dir LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}


	function getTorneoCat($id="") {
		$db = new Db();
		$query = "Select e.*
				  from equipos e
				  where e.idTorneoZona =  '$id'" ;
		$query .= " order by e.nombre";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getByIdEquipo($id="") {
		$db = new Db();
		$query = "Select e.*
				  from equipos e
				  where e.id <>  '$id' and 
				  e.idTorneoZona = (select idTorneoZona from equipos  where id = '$id') order by e.nombre";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getById($id="") {
		$db = new Db();
		$query = "Select e.*
				  from equipos e
				  where e.id =  '$id'";
		$res = $db->getRow($query); 
		$db->close();
		return $res;
	}
	
	function getEquiposSinTorneo($id="") {
		$db = new Db();
		$query = "Select e.*
				  from equipos e
				  where e.id <> '$id'";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

}

?>