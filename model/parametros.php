<?PHP
include_once "mysql.class.php";

class Parametro { 
	var $id;
	var $constante;
	var $nombre;
	var $valor;

	function set($valores){

		$this->id = $valores['id'] ;
		$this->constante = $valores['constante'] ;
		$this->nombre = $valores['nombre'] ;
		$this->valor = $valores['valor'] ;
	}

	function setById($id) {

		$aDatos = $this->getById($id, ARRAY_A); 
		$this->set($aDatos);
		}


	function agregar() {

		$db = new Db();

		$query = "insert into ga_parametros(
			constante, 
			nombre, 
			valor 
		) values (".
		"'" . $this-> constante . "'," .
		"'" . $this-> nombre . "'," .
		"'" . $this-> valor . "'" .
		")";

		$id_insertado = $db->query($query);
		$db->close();
		$this->id = $id_insertado;
		return $id_insertado;
	}


	function eliminar() {

		$db = new Db();

		$query = "delete from ga_parametros where id = ".$this->id ;

		$db->query($query);

		$db->close();

		}


	function modificar() {

		$db = new Db();

		$query = "update ga_parametros set
		  constante = '" . $this->constante . "',
		  nombre = '" . $this->nombre . "',
		  valor = '" . $this->valor . "'
		where id  = ".$this->id ;

		$db->query($query);

		$db->close();

		}

	function modificarValor() {

		$db = new Db();

		$query = "update ga_parametros set
		  valor = '" . $this->valor . "'
		where id  = ".$this->id ;

		$db->query($query);

		$db->close();

		}

	function get($id="") {

		$db = new Db();

		$query = "Select *
		          from ga_parametros
		 		   where 1=1 "; 

		if ($id != "") {
				$query .= " and id = '$id' ";
		}

		 $query .= " order by nombre ";

		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}


	function getById($id="", $output = OBJECT) {

		$db = new Db();

		$query = "Select *
		          from ga_parametros
		 		   where  id = '$id' ";
		$res = $db->getRow($query,"",$output); 
		$db->close();
		return $res;
	}

	function getByConstante($cte="", $output = OBJECT) {

		$db = new Db();

		$query = "Select *
		          from ga_parametros
		 		   where  constante = '$cte' ";
				   
		$res = $db->getRow($query,"",$output); 
		$db->close();
		return $res->valor;
	}



	function getPaginado($filtros, $inicio, $cant, &$total) {
		$db = new Db();

		$query = "Select SQL_CALC_FOUND_ROWS  * 
		            from ga_parametros
		            where 1 = 1" ; 

		if (trim($filtros["fconstante"]) != "" )
			$query.= " and constante like '%". $filtros["fconstante"] ."%'";

		if (trim($filtros["fnombre"]) != "" )
			$query.= " and nombre like '%". $filtros["fnombre"] ."%'";

		if (trim($filtros["fvalor"]) != "" )
			$query.= " and valor like '%". $filtros["fvalor"] ."%'";

		$query .= " LIMIT $inicio,$cant";

		$datos = $db->getResults($query, ARRAY_A); 

		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A);

		$total = ceil( $cant_reg[0]["cant"] / $cant );

		$db->close();

		return $datos;

	}

 }?>