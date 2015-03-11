<?PHP
include_once "include/config.inc.php";
include_once "include/_funciones.php";
include_once "mysql.class.php";

class Beneficios {

	var $id;
	var $titulo;
	var $descripcion;
	var $fotoLogo;

	function Beneficios($id="") {

		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"];
			$this->titulo = $valores[0]["titulo"];
			$this->descripcion = $valores[0]["descripcion"];
			$this->fotoLogo = $valores[0]["fotoLogo"];
		}
	}


	function set($valores){

		$this->id = $valores["id"];
		$this->titulo = $valores["titulo"];
		$this->descripcion = $valores["descripcion"];
		$this->fotoLogo = $valores["fotoLogo"];
	}

	function _setById($id) {

		$aValores = $this->getById($id, ARRAY_A);
		$this->set($aValores);
	}


	function insertar($files) {

		$db = new Db();

		$query = "insert into ga_beneficios(
					titulo,descripcion
				) values (".
				"'".$this->titulo."',".
				"'".$this->descripcion."')";

		$this->id = $db->query($query);
		echo $query;
		if(is_uploaded_file($_FILES['fotoLogo']['tmp_name'])) {
			// Foto Preview
			$name = "ben_".$this->id."_".$files['fotoLogo']['name'];
			$ruta= "../fotos_beneficios/".$name;

			move_uploaded_file($_FILES['fotoLogo']['tmp_name'], $ruta);

			$query = "update ga_beneficios set  fotoLogo = '". $name."'
					  where id = ".$this->id ;

			$db->query($query);

		}



		$db->close();

	}


	function eliminar() {

		$db = new Db();

		$query = "delete from ga_beneficios where id = ".$this->id ;

		$db->query($query);

		$db->close();

	}

	function actualizar($files) {

		$db = new Db();

		$query = "update ga_beneficios set
		          titulo = '". $this->titulo."',
		          descripcion = '". $this->descripcion."'
				  where id = ".$this->id ;

		$db->query($query);

		if(is_uploaded_file($_FILES['fotoLogo']['tmp_name'])) {
			// Foto Preview
			$name = "ben_".$this->id."_".$files['fotoLogo']['name'];
			$ruta= "../fotos_beneficios/".$name;

			move_uploaded_file($_FILES['fotoLogo']['tmp_name'], $ruta);

			$query = "update ga_beneficios set  fotoLogo = '". $name."'
					  where id = ".$this->id ;

			$db->query($query);

		}

		$db->close();

	}



	function getPaginado($filtros, $inicio, $cant, &$total) {

		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  e.*
		          from ga_beneficios e ";



		$query.= "LIMIT $inicio,$cant";


		$datos = $db->getResults($query, ARRAY_A);

		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A);

		$total = ceil( $cant_reg[0]["cant"] / $cant );

		$db->close();

		return $datos;

	}



	function getById($id="") {

		$db = new Db();

		$query = "Select e.*
				  from ga_beneficios e
				  where e.id =  '$id'";

		$res = $db->getRow($query);

		$db->close();

		return $res;

	}

	function get($id="") {

		$db = new Db();

		$query = "Select e.*
				  from ga_beneficios e";

		if ($id != "")
		  $query .= " where e.id =  '$id'";


		$datos = $db->getResults($query, ARRAY_A);

		$db->close();

		return $datos;

	}

}

?>