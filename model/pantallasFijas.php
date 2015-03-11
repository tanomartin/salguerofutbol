<?PHP
include_once "include/config.inc.php";
include_once "mysql.class.php";

class Pantallas {

	var $id;
	var $idTorneoCat;
	var $posiciones;
	var $resultados;
	var $goleadoras;
		
	function Pantallas($id="") {

		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->idTorneoCat = $valores[0]["idTorneoCat"];
			$this->posiciones = $valores[0]["posiciones"]; 
			$this->resultados = $valores[0]["resultados"];
			$this->goleadoras = $valores[0]["goleadoras"]; 
			
		}
	}

	
	function set($valores){
		
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];			
		$this->idTorneoCat = $valores["idTorneoCat"];
		$this->goleadoras = $valores["goleadoras"];
		$this->posiciones = $valores["posiciones"]; 
		$this->resultados = $valores["resultados"];
	}
	
	function _setById($id) {
				
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		

	function insertar($files) {
	
		$db = new Db();

		$query = "insert into ga_pantallas_fijas(
				idTorneoCat
				) values (".
				"'".$this->idTorneoCat."')";
	
		$this->id = $db->query($query); 
		if(is_uploaded_file($_FILES['posiciones']['tmp_name'])) {
			// posiciones
			$name = "pos_".$this->idTorneoCat."_".$files['posiciones']['name'];
			$ruta= "../pantallas_fijas/".$name;
			
			move_uploaded_file($_FILES['posiciones']['tmp_name'], $ruta);

			$query = "update ga_pantallas_fijas set  posiciones = '". $name."'
					  where id = ".$this->id ;

			$db->query($query); 

		}
		
		if(is_uploaded_file($_FILES['resultados']['tmp_name'])) {
			// resultados
			$name1 = "res_".$this->idTorneoCat."_".$files['resultados']['name'];
			$ruta= "../pantallas_fijas/".$name1;
			
			move_uploaded_file($_FILES['resultados']['tmp_name'], $ruta);
		
		// Actualizo en la tabla los idTorneoCats de las imagenes
			$query = "update ga_pantallas_fijas set resultados = '". $name1."'		  
					  where id = ".$this->id ;
				  
			$db->query($query); 
		}

		if(is_uploaded_file($_FILES['goleadoras']['tmp_name'])) {
			// goleadoras
			$name2 = "gol_".$this->idTorneoCat."_".$files['goleadoras']['name'];
			$ruta= "../pantallas_fijas/".$name2;
			
			move_uploaded_file($_FILES['goleadoras']['tmp_name'], $ruta);
		
		// Actualizo en la tabla los idTorneoCats de las imagenes
			$query = "update ga_pantallas_fijas set goleadoras = '". $name2."'		  
					  where id = ".$this->id ;
				  
			$db->query($query); 
		}


		$db->close();

	}


	function eliminar() {
	
		$db = new Db();
			
		$query = "delete from ga_pantallas_fijas where id = ".$this->id ;
	  
		$db->query($query); 
		$db->close();
	
	}
	
	function actualizar($files) {

		$db = new Db();

		if(is_uploaded_file($_FILES['posiciones']['tmp_name'])) {
			// posiciones
			$name = "pos_".$this->idTorneoCat."_".$files['posiciones']['name'];
			$ruta= "../pantallas_fijas/".$name;
			
			move_uploaded_file($_FILES['posiciones']['tmp_name'], $ruta);

			$query = "update ga_pantallas_fijas set  posiciones = '". $name."'
					  where idTorneoCat = ".$this->idTorneoCat ;

			$db->query($query); 

		}
		
		if(is_uploaded_file($_FILES['resultados']['tmp_name'])) {
			// resultados
			$name1 = "res_".$this->idTorneoCat."_".$files['resultados']['name'];
			$ruta= "../pantallas_fijas/".$name1;
			
			move_uploaded_file($_FILES['resultados']['tmp_name'], $ruta);
		
		// Actualizo en la tabla los idTorneoCats de las imagenes
			$query = "update ga_pantallas_fijas set resultados = '". $name1."'		  
					  where idTorneoCat = ".$this->idTorneoCat ;
				  
			$db->query($query); 
		}

		if(is_uploaded_file($_FILES['goleadoras']['tmp_name'])) {
			// goleadoras
			$name2 = "gol_".$this->idTorneoCat."_".$files['goleadoras']['name'];
			$ruta= "../pantallas_fijas/".$name2;
			
			move_uploaded_file($_FILES['goleadoras']['tmp_name'], $ruta);
		
		// Actualizo en la tabla los idTorneoCats de las imagenes
			$query = "update ga_pantallas_fijas set goleadoras = '". $name2."'		  
					  where idTorneoCat = ".$this->idTorneoCat ;
				  
			$db->query($query); 
		}

		$db->close();
	
	}
	
	function get($id="") {
	
		$db = new Db();
		
		$query = "Select e.* from ga_pantallas_fijas e " ;
		

		if ($id != "") {
		
			$query .= " and e.id = '$id' ";
		}
		
		$query .= " order by e.id";

		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	
	}

	function getByTorneoCat($id="") {

		$db = new Db();
		
		$query = "Select e.*
				  from ga_pantallas_fijas e
				  where e.idTorneoCat = ". $id;
		

		$query .= " order by e.id";

		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	
	}


	function getPaginado($filtros, $inicio, $cant, &$total) {

		$orden = ($filtros["filter_order"])?$filtros["filter_order"]:"e.id";
		$dir = ($filtros["filter_order_Dir"])?$filtros["filter_order_Dir"]:"asc"; 


		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  e.*, t.nombre as torneo, c.nombreLargo as categoria
		          from ga_pantallas_fijas e, ga_torneos t, ga_torneos_categorias tc, ga_categorias c
				  where e.idTorneoCat = tc.id and tc.id_torneo = t.id and tc.id_categoria = c.id ";
	

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

}

?>