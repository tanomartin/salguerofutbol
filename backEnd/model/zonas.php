<?PHP
include_once "mysql.class.php";

class Zonas {

	var $id;
	var $nombreLargo;
	var $nombreCorto;	
	var $nombrePagina;	
		
	function Categorias($id="") {
		if ($id != "") {
			$categoria = $this->get($id);
			$this->id = $categoria[0]["id"]; 
			$this->nombreLargo = $categoria[0]["nombreLargo"];
			$this->nombreCorto = $categoria[0]["nombreCorto"];
			$this->nombrePagina = $categoria[0]["nombrePagina"];
		}
	}

	function set($valores){		
		$this->id = ($valores["idreg"])?$valores["idreg"]:$valores["id"]; 
		$this->nombreCorto = $valores["nombreCorto"];
		$this->nombreLargo = $valores["nombreLargo"];
		$this->nombrePagina = $valores["nombrePagina"];
	}
	
	function _setById($id) {
		$aCat = $this->getById($id, ARRAY_A);	
		$this->set($aCat);
	}
		
	function agregar() {
		$db = new Db();		
		$query = "insert into zonas(
				nombreCorto,
				nombreLargo	,nombrePagina		
				) values (".
		"'".$this->nombreCorto."',".
		"'".$this->nombreLargo."',".
		"'".$this->nombrePagina."'".		
		")" ;
		$db->query($query); 
		$db->close();
	}

	function eliminar() {	
		$db = new Db();
		$query = "delete from zonas where id = ".$this->id ;
		$db->query($query); 
		$query = "delete from torneos_zonas where id_zona = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function modificar() {
		$db = new Db();
		$query = "update zonas set 
		          nombreCorto = '". $this->nombreCorto."',
		          nombreLargo  = '". $this->nombreLargo."',
		          nombrePagina 		= '". $this->nombrePagina."' 
				  where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select c.* from zonas c where 1=1 " ;
		if ($id != "") {
			$query .= " and c.id = '$id' ";
		}
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}

	function getById($id, $output = OBJECT) {
		$db = new Db();
		$query = "Select c.*
				  From zonas c
				  WHERE  c.id = '".$id."'";
		$oCat = $db->getRow($query,"",$output); 
		$db->close();
		return $oCat;
	}
	
	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  c.* 
				   from zonas c 
				  where 1=1 ";
		if (trim($filtros["fnombreCorto"]) != "")		 
			$query.= " and c.nombreCorto like '%".strtoupper($_REQUEST["fnombreCorto"])."%'";		  
		if (trim($filtros["fnombreLargo"]) != "")		 
			$query.= " and c.nombreLargo like '%".strtoupper($_REQUEST["fnombreLargo"])."%'";		  
		$query.= " LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}
	
	function getByCategoriasDisponibles($id, $output = OBJECT) {
		$db = new Db();
		$query = "Select *
				  From zonas
				  where id not in(
					Select id_zona
				  	From torneos_zonas								  
					Where id_torneo = ".$id.")";
		$aDatos = $db->getResults($query,ARRAY_A); 
		$db->close();
		return $aDatos;
	}

}

?>