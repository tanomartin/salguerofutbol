<?PHP
include_once "include/config.inc.php";
include_once "include/_funciones.php";
include_once "mysql.class.php";

class Equipos {

	var $id;
	var $nombre;
	var $idTorneoCat;
	var $descuento_puntos;
	var $descripcion;
	var $fotoPreview;
	var $fotoGrande;
	var $email;
		
	function Equipos($id="") {

		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->nombre = $valores[0]["nombre"];			
			$this->idTorneoCat = $valores[0]["idTorneoCat"];
			$this->descuento_puntos = $valores[0]["descuento_puntos"]; 
			$this->descripcion = $valores[0]["descripcion"];
			$this->fotoPreview = $valores[0]["fotoPreview"]; 
			$this->fotoGrande = $valores[0]["fotoGrande"];
			$this->email = $valores[0]["email"];
			
		}
	}

	
	function set($valores){
		
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];			
		$this->idTorneoCat = $valores["idTorneoCat"];
		$this->descuento_puntos = ($valores["descuento_puntos"])?$valores["descuento_puntos"]:0; 
		$this->descripcion = $valores["descripcion"];
		$this->fotoPreview = $valores["fotoPreview"]; 
		$this->fotoGrande = $valores["fotoGrande"];
		$this->email = $valores["email"];
		
	}
	
	function _setById($id) {
				
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		

	function insertar($files) {
	
		$db = new Db();

		$query = "insert into ga_equipos(
				idTorneoCat,nombre,descuento_puntos,email,descripcion
				) values (".
				"'".$this->idTorneoCat."',".
				"'".$this->nombre."',".				
				"'".$this->descuento_puntos."',".
				"'".$this->email."',".				
				"'".$this->descripcion."')";
	
		$this->id = $db->query($query); 
		
		if(is_uploaded_file($_FILES['fotoPreview']['tmp_name'])) {
			// Foto Preview
			$path_parts = pathinfo($_FILES["fotoPreview"]["name"]);
			$extension = $path_parts['extension'];


			$name = "pre_".$this->id."_".time().".".$extension;
			$ruta= "../fotos_equipos/".$name;
	
			$query = "update ga_equipos set  fotoPreview = '". $name."'
					  where id = ".$this->id ;

			$db->query($query); 

		}
		
		if(is_uploaded_file($_FILES['fotoGrande']['tmp_name'])) {
			// Foto Grande
			// Foto Preview
			$path_parts = pathinfo($_FILES["fotoGrande"]["name"]);
			$extension = $path_parts['extension'];
			
			$name1 = "gra_".$this->id."_".time().".".$extension;
			$ruta= "../fotos_equipos/".$name1;
			
			move_uploaded_file($_FILES['fotoGrande']['tmp_name'], $ruta);
		
		// Actualizo en la tabla los idTorneoCats de las imagenes
			$query = "update ga_equipos set fotoGrande = '". $name1."'		  
					  where id = ".$this->id ;
				  
			$db->query($query); 
		}
				  

		$db->close();

	}


	function eliminar() {
	
		$db = new Db();
			
		$query = "delete from ga_equipos where id = ".$this->id ;
	  
		$db->query($query); 
		$db->close();
	
	}
	
	function actualizar($files) {

		$db = new Db();

		$query = "update ga_equipos set 
		          nombre = '". $this->nombre."',		
		          idTorneoCat = '". $this->idTorneoCat."',
		          descuento_puntos = '". $this->descuento_puntos."',
				  email = '". $this->email."',
		          descripcion = '". $this->descripcion."'
				  where id = ".$this->id ;
				  
		$db->query($query); 

		if(is_uploaded_file($_FILES['fotoPreview']['tmp_name'])) {
			// Foto Preview
			$path_parts = pathinfo($_FILES["fotoPreview"]["name"]);
			$extension = $path_parts['extension'];
	
			$name = "pre_".$this->id."_".time().".".$extension;
			$ruta= "../fotos_equipos/".$name;
	
			if ( move_uploaded_file($_FILES['fotoPreview']['tmp_name'], $ruta))
				echo "subio";
			else	
				echo "no subio";


			$query = "update ga_equipos set  fotoPreview = '". $name."'
					  where id = ".$this->id ;

			$db->query($query); 

		}
		
		if(is_uploaded_file($_FILES['fotoGrande']['tmp_name'])) {
			// Foto Grande
			$path_parts = pathinfo($_FILES["fotoGrande"]["name"]);
			$extension = $path_parts['extension'];
			
			$name1 = "gra_".$this->id."_".time().".".$extension;
			$ruta= "../fotos_equipos/".$name1;
	
			move_uploaded_file($_FILES['fotoGrande']['tmp_name'], $ruta);
		
		// Actualizo en la tabla los idTorneoCats de las imagenes
			$query = "update ga_equipos set fotoGrande = '". $name1."'		  
					  where id = ".$this->id ;
				  
			$db->query($query); 
		}

		$db->close();
	
	}
	
	function get($id="") {
	
		$db = new Db();
		
		$query = "Select e.*, tc.id_torneo, tc.	id_categoria, tc.id as idTorneoCat,t.nombre as torneo, c.nombreLargo  as categoria
				  from ga_equipos e, ga_torneos t, ga_torneos_categorias tc, ga_categorias c
				  where e.idTorneoCat = tc.id and tc.id_torneo = t.id and tc.id_categoria = c.id " ;
		

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
				  from ga_equipos e
				  where e.idTorneoCat = ". $id;
		

		$query .= " order by e.nombre";


		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	
	}


	function getPaginado($filtros, $inicio, $cant, &$total) {

		$orden = ($filtros["filter_order"])?$filtros["filter_order"]:"e.id";
		$dir = ($filtros["filter_order_Dir"])?$filtros["filter_order_Dir"]:"asc"; 


		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  e.*, t.nombre as torneo, c.nombreLargo as categoria
		          from ga_equipos e, ga_torneos t, ga_torneos_categorias tc, ga_categorias c
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


	function getTorneoCat($id="") {
	
		$db = new Db();
		
		$query = "Select e.*
				  from ga_equipos e
				  where e.idTorneoCat =  '$id'" ;
		
		$query .= " order by e.nombre";

		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	
	}

	function getByIdEquipo($id="") {
	
		$db = new Db();
		
		$query = "Select e.*
				  from ga_equipos e
				  where e.id <>  '$id' and 
				  e.idTorneoCat = (select idTorneoCat from ga_equipos  where id = '$id')";
		
		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	
	}


	function getById($id="") {
	
		$db = new Db();
		
		$query = "Select e.*
				  from ga_equipos e
				  where e.id =  '$id'";
		
		$res = $db->getRow($query); 
	
		$db->close();
		
		return $res;
	
	}
	
	function accesoCorrecto($id="",$pass="") {
		$db = new Db();
		
		$query = "Select count(*) as cantidad from ga_equipos_pass_reserva e where id = '$id' and password = '".md5($pass)."'";
		
		$res = $db->getRow($query); 
	
		$db->close();
		
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	}

	function tieneFechaLibre($idTorneo="", $idEquipo= "") {
		$db = new Db();
		
		$query = "Select count(*) as cantidad from ga_reservas e, ga_fechas f where f.idTorneoCat = '$idTorneo' and f.id = e.id_fecha and e.id_equipo = '$idEquipo' and e.fecha_libre = 1";

		$res = $db->getRow($query); 
	
		$db->close();
		
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	}

	function tieneReserva($idFecha="", $idEquipo= "") {
		$db = new Db();
		
		$query = "Select e.id as id from ga_reservas e where id_Fecha = '$idFecha' and id_equipo = '$idEquipo'";
		
		$res = $db->getRow($query); 
	
		$db->close();
		
		if($res->id == 0) {
			return 0;
		} else {
			return $res->id;
		}
	}
	
	function getPassword($idEquipo= "") {
		$db = new Db();
		
		$query = "Select * from ga_equipos_pass_reserva where id = $idEquipo";
		
		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	}
	
	function setPassword($idEquipo= "", $pass="") {
		$db = new Db();
		
		$query = "Delete from ga_equipos_pass_reserva where id = $idEquipo";
		
		$db->query($query);
		
		$query = "Insert into ga_equipos_pass_reserva(id,password) value ($idEquipo,'".md5($pass)."')";
		
		$db->query($query);
	
		$db->close();
	}
	
	function seEnvioCorreo($idEquipo= "", $idFecha="", $tabla="") {
		$db = new Db();
		
		if ($tabla == "r") {
			$query = "Select count(*) as cantidad from ga_correo_reservas where id_equipo = '$idEquipo' and id_fecha = '$idFecha'";
		}
		if ($tabla == "c") {
			$query = "Select count(*) as cantidad from ga_correo_confirmacion where id_equipo = '$idEquipo' and id_fecha = '$idFecha'";
		}

		$res = $db->getRow($query); 
	
		$db->close();
		
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function eliminarCorreo($idEquipo= "", $idFecha="", $tabla="") {
		$db = new Db();

		$today = date('Y-m-d');
		
		if ($tabla == "r") {
			$query = "delete from ga_correo_reservas where id_equipo = $idEquipo and id_fecha = $idFecha";
		}
		if ($tabla == "c") {
			$query = "delete from ga_correo_confirmacion where id_equipo = $idEquipo and id_fecha = $idFecha";
		}
		
		$db->query($query);
	
		$db->close();
	}

}

?>