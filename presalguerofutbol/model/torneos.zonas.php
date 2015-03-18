<?PHP
	include_once "include/_funciones.php";
	include_once "mysql.class.php";

class TorneoZona {

	var $id;  	
	var $id_torneo;
	var $id_zona; 	
			
	function TorneoZona($id="") {
		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id_torneo_zona"]; 
			$this->id_torneo = $valores[0]["id_torneo"]; 
			$this->id_zona = $valores[0]["id_categoria"]; 
		}
	}

	
	function set($valores){
			$this->id = $valores["id_torneo_zona"]; 
			$this->id_torneo = $valores["id_torneo"]; 
			$this->id_zona = $valores["id_categoria"]; 
	}

	function _setById($id) {			
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		
	function insertar() {
		$db = new Db();
		$query = "insert into torneos_zonas(
				id_torneo, id_zona
				) values (".
				"'".$this->id_torneo."',".
				"'".$this->id_zona."'".				
				")" ;
		$id_insertado = $db->query($query); 
		$db->close();
		return $id_insertado;
	}


	function eliminar() {	
		$db = new Db();
		$query = "delete from torneos_zonas where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	
	function get($id="") {
		$db = new Db();
		$query = "Select * from torneos_zonas" ;
		if ($id != "") {
			$query .= " where id = '$id' ";
		}
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getById($id, $output = OBJECT) {
		$db = new Db();
		$query = "Select *
				  From torneos_zonas u
				  Where id = '".$id."'";
		$oDatos = $db->getRow($query,"",$output); 
		$db->close();
		return $oDatos;
	}
	
	
	function getByTorneo($id,$order ="") {
		$db = new Db();
		$query = "Select tz.*, z.nombreLargo,z.nombreCorto,z.nombrePagina
				  From torneos_zonas tz, zonas z
				  Where tz.id_zona = z.id and id_torneo = '".$id."'";
		if ($order != "")
			$query.= " order by $order";
		else
			$query.= " order by id_zona ";
		$aDatos = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $aDatos;
	}
	
	function getByTorneoSub($id) {
		$db = new Db();
		$query = "Select tz.*, z.nombreLargo, z.nombreCorto, z.nombrePagina, z.id as idZona
				  From torneos_zonas tz 
				  left join zonas z on tz.id_zona = z.id			  
				  Where id_torneo = '".$id."' order by idZona,id";
		$aDatos = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $aDatos;
	}
	
	function getByTorneoFechas($id,$order ="") {
		$db = new Db();
		$query = "Select tz.*, z.nombreLargo, z.nombreCorto, z.nombrePagina
				  From torneos_zonas tz left join zonas z
				  on tz.id_zona = z.id 
				  where id_torneo = '".$id."'";
		if ($order != "")
			$query.= " order by $order";
		else
			$query.= " order by id_zona";
		$aDatos = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $aDatos;
	}
	
}

?>