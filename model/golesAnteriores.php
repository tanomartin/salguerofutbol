<?PHP
include_once "mysql.class.php";

class GolesAnt {

	var $id;
	var $id_torneo;
	var $id_equipo;
	var $id_jugadora;
	var $goles;
	
	function Equipos($id="") {

		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->id_torneo = $valores[0]["id_torneo"]; 
			$this->id_equipo = $valores[0]["id_equipo"];
			$this->id_jugadora = $valores[0]["id_jugadora"]; 
			$this->goles = ($valores[0]["goles"])?$valores[0]["goles"]:0; 
			}
	}

	
	function set($valores){
		
		$this->id = $valores["id"]; 
		$this->id_torneo = $valores["id_torneo"]; 
		$this->id_categoria = $valores["id_categoria"];
		$this->id_equipo = $valores["id_equipo"]; 
		$this->id_jugadora = $valores["id_jugadora"];
		$this->goles = ($valores["goles"])?$valores["goles"]:0; 

	}
	
	function _setById($id) {
				
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		

	function insertar() {
	
		$db = new Db();

		$query = "insert into ga_goles_anteriores(
				id_torneo,id_categoria,id_equipo,id_jugadora,goles
				) values (".
				"'".$this->id_torneo."',".
				"'".$this->id_categoria."',".				
				"'".$this->id_equipo."',".
				"'".$this->id_jugadora."',".
				"'".$this->goles."')";
		
		$this->id = $db->query($query); 

		//echo $query;
		$db->close();

	}


	function eliminar($id_torneo,$id_categoria,$id_equipo) {
	
		$db = new Db();
			
		$query = "delete  from ga_goles_anteriores 
				  WHERE id_torneo = ".$id_torneo." and id_categoria = ".$id_categoria." and id_equipo = ".$id_equipo ;

		$db->query($query); 
		$db->close();
	
	}
	
	


	function get($id_torneo,$id_categoria,$id_equipo) {
	
		$db = new Db();
		
		$query = "Select id_jugadora, goles
				  from ga_goles_anteriores 
				  WHERE id_torneo = ".$id_torneo." and id_categoria = ".$id_categoria." and id_equipo = ".$id_equipo ;
		

		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	
	}


	function getPaginado($filtros, $inicio, $cant, &$total) {

		$orden = ($filtros["filter_order"])?$filtros["filter_order"]:"x.id";
		$dir = ($filtros["filter_order_Dir"])?$filtros["filter_order_Dir"]:"asc"; 


		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  x.*, e1.nombre as equipo1, t.nombre as torneo, c.nombreCorto as categoria
		          from ga_fixture x, ga_fechas f,ga_torneos_categorias tc, ga_torneos t, ga_categorias c, ga_equipos e1
				  where x.idFecha = f.id and
				  f.idTorneoCat = tc.id and
				  tc.id_torneo = t.id and
				  tc.id_categoria = c.id and
				  x.idEquipo1 = e1.id ";
	

		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and e1.nombre like '%".strtoupper($filtros["fnombre"])."%'";		  

		if (trim($filtros["ftorneo"]) != "")		 
			$query.= " and  t.nombre  like '%".strtoupper($filtros["ftorneo"])."%'";		  

		if (trim($filtros["fcategoria"]) != "")		 
			$query.= " and  c.nombreCorto like '%".strtoupper($filtros["fcategoria"])."%'";		  

		$query.= " order by  $orden $dir LIMIT $inicio,$cant";
		
		$datos = $db->getResults($query, ARRAY_A); 

		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
	
		$total = ceil( $cant_reg[0]["cant"] / $cant );

		$db->close();

		return $datos;	

	}



}

?>