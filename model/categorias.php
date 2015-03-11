<?PHP
include_once "include/config.inc.php";
include_once "mysql.class.php";

class Categorias {

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
				
		$query = "insert into ga_categorias(
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

		$query = "delete from ga_categorias where id = ".$this->id ;
				  
		$db->query($query); 
		
		$query = "delete from ga_torneos_categorias where id_categoria = ".$this->id ;
				  
		$db->query($query); 

$db->close();
	
	}
	
	function modificar() {

		$db = new Db();
		$query = "update ga_categorias set 
		          nombreCorto = '". $this->nombreCorto."',
		          nombreLargo  = '". $this->nombreLargo."',
		          nombrePagina 		= '". $this->nombrePagina."' 
				  where id = ".$this->id ;
				  
		$db->query($query); 
		$db->close();
	
	}
	
	function get($id="") {
	
		$db = new Db();
		$query = "Select c.* from ga_categorias c where 1=1 " ;
		
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
				  From ga_categorias c
				  WHERE  c.id = '".$id."'";

		$oCat = $db->getRow($query,"",$output); 

		$db->close();
		return $oCat;

	}
	

	function getPaginado($filtros, $inicio, $cant, &$total) {

		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  c.*n 
				   from ga_categorias c 
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
				  From ga_categorias
				  where id not in(
					Select 	id_categoria
				  	From ga_torneos_categorias								  
					Where id_torneo = '".$id." and id_padre = -1'
				 )";

		$aDatos = $db->getResults($query,ARRAY_A); 
		
		$db->close();
		return $aDatos;

	}

	function getBySubCategoriasDisponibles($id, $id_categoria, $output = OBJECT) {
		$db = new Db();
		$query = "Select *
				  From ga_categorias
				  where id not in(
					Select 	id_categoria
				  	From ga_torneos_categorias								  
					Where id_torneo = '".$id."'
				 ) and id not in( 
 					Select 	id_categoria
				  	From ga_torneos_categorias								  
					Where id_padre = '".$id_categoria."'

				 )";

		$aDatos = $db->getResults($query,ARRAY_A); 
		
		$db->close();
		return $aDatos;

	}

}

?>