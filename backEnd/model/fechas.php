<?PHP
include_once "mysql.class.php";

class Fechas {

	var $id;
	var $nombre;
	var $idTorneoZona;
	var $fechaIni;
	var $fechaFin;
		
	function Fechas($id="") {
		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->nombre = $valores[0]["nombre"];			
			$this->idTorneoZona = $valores[0]["idTorneoCat"];
			$this->fechaIni = cambiaf_a_mysql($valores[0]["fechaIni"]); 
			$this->fechaFin = cambiaf_a_mysql($valores[0]["fechaFin"]);
		}
	}

	function set($valores){		
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];			
		$this->idTorneoZona = $valores["idTorneoCat"];
		$this->fechaIni = cambiaf_a_mysql($valores["fechaIni"]); 
		$this->fechaFin = cambiaf_a_mysql($valores["fechaFin"]);
	}
	
	function _setById($id) {			
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		

	function insertar() {
		$db = new Db();
		$query = "insert into fechas(
				idTorneoZona,nombre,fechaIni,fechaFin
				) values (".
				"'".$this->idTorneoZona."',".
				"'".$this->nombre."',".				
				"'".$this->fechaIni."',".
				"'".$this->fechaFin."')";
		$this->id = $db->query($query); 
		$db->close();
	}

	function eliminar() {
		$db = new Db();
		$query = "delete from fechas where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function actualizar() {
		$db = new Db();
		$query = "update fechas set 
		          nombre = '". $this->nombre."',		
		          idTorneoZona = '". $this->idTorneoZona."',
		          fechaIni = '". $this->fechaIni."',
		          fechaFin = '". $this->fechaFin."'
				  where id = ".$this->id ; 
		$db->query($query); 
		$db->close();
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select e.*, tz.id_torneo, tz.id_zona, tz.id as idTorneoZona, t.nombre as torneo, z.nombreCorto as zona
				  from fechas e, torneos t, torneos_zonas tz, zonas z
				  where e.idTorneoZona = tz.id and tz.id_torneo = t.id and tz.id_zona = z.id " ;
		if ($id != "") {
			$query .= " and e.id = '$id' ";
		}
		$query .= " order by e.nombre";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getIdTorneoCat($id="",$orden="") {
		$db = new Db();
		$query = "Select e.*, tz.id_torneo, tz.id_zona, tz.id as idTorneoZona, t.nombre as torneo, z.nombreCorto as zona
				  from fechas e, torneos t, torneos_zonas tz, zonas z
				  where e.idTorneoZona = tz.id and tz.id_torneo = t.id and tz.id_zona = z.id " ;
		if ($id != "") {
			$query .= " and e.idTorneoZona = '$id' ";
		}
		$order = " order by e.nombre";
		if ( $orden != "")
			$order = " order by e.".$orden;
		$query .= $order;
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function getFechaProxima($id="") {
		$db = new Db();
		$hoy = date('Y-m-d');
		$query = "Select f.*, tz.id_torneo, tz.id_zona, tz.id as idTorneoZona, t.nombre as torneo, z.nombreCorto as zona
				  from fechas f, torneos t, torneos_zonas tz, zonas z
				  where f.idTorneoZona = tz.id and tz.id_torneo = t.id and tz.id_zona = z.id and (f.fechaIni >= '$hoy' or (f.fechaIni < '$hoy' and f.fechaFin >= '$hoy'))";
		if ($id != "") {
			$query .= " and f.idTorneoZona = '$id'";
		}
		$query .= " order by f.fechaIni ASC Limit 1";
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}
	
	function getFechaUltima($id="",$orden="") {
		$db = new Db();
		$hoy = date('Y-m-d');
		$query = "Select f.*, tz.id_torneo, tz.id_zona, tz.id as idTorneoZona, t.nombre as torneo, z.nombreCorto as zona
				  from fechas f, torneos t, torneos_zonas tz, zonas z
				  where f.idTorneoZona = tz.id and tz.id_torneo = t.id and tz.id_zona = z.id and f.fechaFin <= '$hoy'" ;
		if ($id != "") {
			$query .= " and f.idTorneoZona = '$id'";
		}
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getPaginado($filtros, $inicio, $cant, &$total) {
		$orden = ($filtros["filter_order"])?$filtros["filter_order"]:"e.id";
		$dir = ($filtros["filter_order_Dir"])?$filtros["filter_order_Dir"]:"asc"; 
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  e.*, t.nombre as torneo, z.nombreCorto as zona
		          from fechas e, torneos t, torneos_zonas tz, zonas z
				  where e.idTorneoZona = tz.id and tz.id_torneo = t.id and tz.id_zona = z.id ";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and e.nombre like '%".strtoupper($filtros["fnombre"])."%'";		  
		if (trim($filtros["ftorneo"]) != "")		 
			$query.= " and  t.nombre  like '%".strtoupper($filtros["ftorneo"])."%'";		  
		if (trim($filtros["fcategoria"]) != "")		 
			$query.= " and  z.nombreCorto like '%".strtoupper($filtros["fcategoria"])."%'";		  
		$query.= " order by e.fechaIni DESC, e.fechaFin DESC  LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}
	
	function existeFecha($idFecha="") {
		$db = new Db();
		$query = "select count(*) as cantidad from fechas where 
		          nombre like '". $this->nombre."' and
		          idTorneoZona = '". $this->idTorneoZona."' and
		          id <> '$idFecha'";
		$res = $db->getRow($query); 
		//print($query."<br>");
		$db->close();
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	}
	
}

?>