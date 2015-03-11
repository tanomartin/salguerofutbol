<?PHP
include_once "include/config.inc.php";
include_once DIR_INC . _DB1_ . ".class.php";

class Equipoideal { 
	var $id;
	var $idTorneoCat;
	var $idJugadora;
	var $idPosicion;

	function set($valores){

		$this->id = $valores['id'] ;
		$this->idTorneoCat = $valores['idTorneoCat'] ;
		$this->idJugadora = $valores['idJugadora'] ;
		$this->idPosicion = $valores['idPosicion'] ;
	}

	function setById($id) {

		$aDatos = $this->getById($id, ARRAY_A); 
		$this->set($aDatos);
		}


	function agregar() {

		$db = new Db();

		$query = "delete from ga_equipoideal where idTorneoCat = '".$this-> idTorneoCat ."' and idPosicion ='". $this-> idPosicion ."'";
		
		$db->query($query);
		
		$query = "insert into ga_equipoideal(
			idTorneoCat, 
			idJugadora, 
			idPosicion 
		) values (".
		"'" . $this-> idTorneoCat . "'," .
		"'" . $this-> idJugadora . "'," .
		"'" . $this-> idPosicion . "'" .
		")";

		$id_insertado = $db->query($query);
		$db->close();
		$this->id = $id_insertado;
		return $id_insertado;
	}


	function eliminar() {

		$db = new Db();

		$query = "delete from ga_equipoideal where id = ".$this->id ;

		$db->query($query);

		$db->close();

		}


	function modificar() {

		$db = new Db();

		$query = "update ga_equipoideal set
		  idTorneoCat = '" . $this->idTorneoCat . "',
		  idJugadora = '" . $this->idJugadora . "',
		  idPosicion = '" . $this->idPosicion . "'
		where id  = ".$this->id ;

		$db->query($query);

		$db->close();

		}


	function get($id="") {

		$db = new Db();

		$query = "Select *
		          from ga_equipoideal
		 		   where 1=1 "; 

		if ($id != "") {
				$query .= " and id = '$id' ";
		}

		 $query .= " order by id ";

		$res = $db->getResults($query, ARRAY_A);
		$db->close();
		return $res;
	}


	function getById($id="", $output = OBJECT) {

		$db = new Db();

		$query = "Select *
		          from ga_equipoideal
		 		   where  id = '$id' ";
		$res = $db->getRow($query,"",$output); 
		$db->close();
		return $res;
	}

	function getByIdTorneoCat($idTorneoCat) {

		$db = new Db();

		$query = "Select e.*, j.nombre as jugadora,eq.nombre as equipo
		          from ga_equipoideal e, ga_jugadoras j, ga_equipos eq
		 		   where  e.idTorneoCat = '$idTorneoCat'  and j.id  = e.idJugadora  and eq.id  = j.idEquipo and e.idTorneoCat = eq.idTorneoCat 
				   order by e.idPosicion";


		$res = $db->getResults($query, ARRAY_A);
		
		$db->close();
		
		return $res;
	}


	function getPaginado($filtros, $inicio, $cant, &$total, $orden, $dir) {
		$db = new Db();

		$query = "Select SQL_CALC_FOUND_ROWS
		            * 
		            from ga_equipoideal
		            where 1 = 1" ; 

		if (trim($filtros["fidTorneoCat"]) != "" )
			$query.= " and idTorneoCat = '". $filtros["fidTorneoCat"] ."'";

		if (trim($filtros["fidJugadora"]) != "" )
			$query.= " and idJugadora = '". $filtros["fidJugadora"] ."'";

		if (trim($filtros["fidPosicion"]) != "" )
			$query.= " and idPosicion like '%". $filtros["fidPosicion"] ."%'";

		$query .= " order by $orden $dir LIMIT $inicio,$cant";

		$datos = $db->getResults($query, ARRAY_A); 

		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A);

		$total = ceil( $cant_reg[0]["cant"] / $cant );

		$db->close();

		return $datos;

	}

 } 
 ?> 