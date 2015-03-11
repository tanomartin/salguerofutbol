<?PHP
include_once "mysql.class.php";

class Fixture {

	var $id;
	var $idFecha;
	var $idEquipo1;
	var $idEquipo2;
	var $observaciones;
	var $fechaPartido;
	var $horaPartido;
	var $golesEquipo1;
    var $golesEquipo2;
	var $amonestadosEquipo1;
    var $amonestadosEquipo2;
	var $expulsadosEquipo1;
    var $expulsadosEquipo2;
    var $suspendido;
    var $idSede;
    var $cancha;	
	
	function Equipos($id="") {

		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->idFecha = $valores[0]["idFecha"]; 
			$this->idEquipo1 = $valores[0]["idEquipo1"];
			$this->idEquipo2 = $valores[0]["idEquipo2"]; 
			$this->observaciones = $valores[0]["observaciones"];
			$this->fechaPartido = cambiaf_a_mysql($valores[0]["fechaPartido"]);
			$this->horaPartido = $valores[0]["horaPartido"];
			$this->idSede = $valores[0]["idSede"];
			$this->cancha = $valores[0]["cancha"];
			$this->golesEquipo1 = ($valores[0]["golesEquipo1"])?$valores[0]["golesEquipo1"]:-1; 
			$this->golesEquipo2 = ($valores[0]["golesEquipo2"])?$valores[0]["golesEquipo2"]:-1; 
			$this->amonestadosEquipo1 = ($valores[0]["amonestadosEquipo1"])?$valores[0]["amonestadosEquipo1"]:0; 
			$this->amonestadosEquipo2 = ($valores[0]["amonestadosEquipo2"])?$valores[0]["amonestadosEquipo2"]:0; 
			$this->expulsadosEquipo1 = ($valores[0]["expulsadosEquipo1"])?$valores[0]["expulsadosEquipo1"]:0; 
			$this->expulsadosEquipo2 = ($valores[0]["expulsadosEquipo2"])?$valores[0]["expulsadosEquipo2"]:0; 
			$this->suspendido = ($valores[0]["suspendido"]=='on')?1:0; 
			}
	}

	
	function set($valores){
		
		$this->id = $valores["id"]; 
		$this->idFecha = $valores["idFecha"]; 
		$this->idEquipo1 = $valores["idEquipo1"];
		$this->idEquipo2 = $valores["idEquipo2"]; 
		$this->observaciones = $valores["observaciones"];
		$this->fechaPartido = cambiaf_a_mysql($valores["fechaPartido"]);
		$this->horaPartido = $valores["horaPartido"];
		$this->idSede = $valores["idSede"];
		$this->cancha = $valores["cancha"];
		$this->golesEquipo1 = ($valores["golesEquipo1"])?$valores["golesEquipo1"]:-1; 
		$this->golesEquipo2 = ($valores["golesEquipo2"])?$valores["golesEquipo2"]:-1; 
		$this->amonestadosEquipo1 = ($valores["amonestadosEquipo1"])?$valores["amonestadosEquipo1"]:0; 
		$this->amonestadosEquipo2 = ($valores["amonestadosEquipo2"])?$valores["amonestadosEquipo2"]:0; 
		$this->expulsadosEquipo1 = ($valores["expulsadosEquipo1"])?$valores["expulsadosEquipo1"]:0; 
		$this->expulsadosEquipo2 = ($valores["expulsadosEquipo2"])?$valores["expulsadosEquipo2"]:0; 
		$this->suspendido = ($valores["suspendido"]=='on')?1:0; 

	}
	
	function _setById($id) {
				
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		

	function insertar() {
	
		$db = new Db();

		$query = "insert into ga_fixture(
				idEquipo1,idFecha,idEquipo2,observaciones,fechaPartido,horaPartido,idSede,cancha,golesEquipo1,golesEquipo2,
				amonestadosEquipo1,amonestadosEquipo2,expulsadosEquipo1,expulsadosEquipo2,suspendido
				) values (".
				"'".$this->idEquipo1."',".
				"'".$this->idFecha."',".				
				"'".$this->idEquipo2."',".
				"'".$this->observaciones."',".
				"'".$this->fechaPartido."',".
				"'".$this->horaPartido."',".
				"'".$this->idSede."',".
				"'".$this->cancha."',".				
				"'".$this->golesEquipo1."',".
				"'".$this->golesEquipo2."',".
				"'".$this->amonestadosEquipo1."',".
				"'".$this->amonestadosEquipo2."',".				
				"'".$this->expulsadosEquipo1."',".
				"'".$this->expulsadosEquipo2."',".
				"'".$this->suspendido."')";
		
		$this->id = $db->query($query); 

		//echo $query;
		$db->close();

	}


	function eliminar() {
	
		$db = new Db();
			
		$query = "delete from ga_fixture where id = ".$this->id ;
	  
		$db->query($query); 
		
		$query = "delete from ga_resultados where idFixture = ".$this->id ;
	  
		$db->query($query); 
		
		$query = "delete from ga_partidos_confirmados where id_partido = ".$this->id ;
	  
		$db->query($query); 

		$db->close();
	
	}
	
	function actualizar($files) {

		$db = new Db();

		$query = "update ga_fixture set 
		          idFecha = '". $this->idFecha."',		
		          idEquipo1 = '". $this->idEquipo1."',
		          idEquipo2 = '". $this->idEquipo2."',
		          observaciones = '". $this->observaciones."',
		          fechaPartido = '". $this->fechaPartido."',
		          horaPartido = '". $this->horaPartido."',
		          idSede = '". $this->idSede."',
		          cancha = '". $this->cancha."',
		          golesEquipo1 = '". $this->golesEquipo1."',
		          golesEquipo2 = '". $this->golesEquipo2."',
		          amonestadosEquipo1 = '". $this->amonestadosEquipo1."',
		          amonestadosEquipo2 = '". $this->amonestadosEquipo2."',
		          expulsadosEquipo1 = '". $this->expulsadosEquipo1."',
		          expulsadosEquipo2 = '". $this->expulsadosEquipo2."',
		          suspendido = '". $this->suspendido."'
				  where id = ".$this->id ;
				  
		$db->query($query); 
	
		$db->close();
	
	}
	
	function modicarCampoValor($campo, $valor,$campoWhere, $valorWhere) {

		$db = new Db();

		$query = "update ga_fixture set ".$campo ." = ".$valor." 
				  where ". $campoWhere ." = '".$valorWhere."'" ;
				  
		$db->query($query); 
	
		$db->close();
	
	}
	

function get($id="") {
	
		$db = new Db();
		
		$query = "Select e.*, tc.id_torneo, tc.	id_categoria, tc.id as idTorneoCat
				  from ga_fixture e,  ga_torneos_categorias tc, ga_fechas f
				  WHERE e.idFecha = f.id AND tc.id = f.idTorneoCat " ;
		

		if ($id != "") {
		
			$query .= " and e.id = '$id' ";
		}
		
		
		$query .= " order by e.idFecha";

		$res = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $res;
	
	}


	function getPaginado($filtros, $inicio, $cant, &$total) {

		$orden = ($filtros["filter_order"])?$filtros["filter_order"]:"x.id";
		$dir = ($filtros["filter_order_Dir"])?$filtros["filter_order_Dir"]:"desc"; 


		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  x.*, f.nombre as fecha, e1.nombre as equipo1,e2.nombre as equipo2, t.nombre as torneo, c.nombreCorto as categoria
		          from ga_fixture x
				  left join  ga_equipos e1 on x.idEquipo1 = e1.id 
				  left join  ga_equipos e2  on x.idEquipo2 = e2.id 
				  left join  ga_fechas f on  x.idFecha = f.id
				  left join  ga_torneos_categorias tc on f.idTorneoCat = tc.id
				  left join  ga_torneos t on tc.id_torneo = t.id
				  left join  ga_categorias c  on  tc.id_categoria = c.id 
				  where 1 = 1 ";
	

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
	
	function getByFecha($fecha){

		$db = new Db();

       $query = "Select  x.*, e1.nombre as equipo1,e2.nombre as equipo2, s.nombre as sede, f.nombre as nombreFecha
		          from ga_fixture x, ga_fechas f,ga_sedes s, ga_equipos e1, ga_equipos e2 
				  where x.idFecha = f.id and
				  x.idSede = s.id and
				  x.idEquipo1 = e1.id and
				  x.idEquipo2 = e2.id
				  and f.id=".$fecha;
	
		$query.= " order by  fechaPartido";

		$datos = $db->getResults($query, ARRAY_A); 
		
		$db->close();

		return $datos;	

	}
	
	function getByFechaEquipo($fecha, $equipo){

		$db = new Db();

       $query = "Select  x.*, e1.nombre as equipo1,e2.nombre as equipo2, s.nombre as sede, f.nombre as nombreFecha
		          from ga_fixture x, ga_fechas f,ga_sedes s, ga_equipos e1, ga_equipos e2 
				  where x.idFecha = f.id and
				  x.idSede = s.id and
				  x.idEquipo1 = e1.id and
				  x.idEquipo2 = e2.id
				  and f.id=".$fecha." and (x.idEquipo1 =".$equipo." || x.idEquipo2=".$equipo.")";
	
		$query.= " order by  horaPartido DESC";

		$datos = $db->getResults($query, ARRAY_A); 
		
		$db->close();

		return $datos;	

	}


	function getByidTorneoCat($idTorneoCat){

		$db = new Db();

       $query = "Select  x.* 
		          from ga_fixture x, ga_fechas f 
				  where x.idFecha = f.id and 
				  f.fechaIni < '".date("Y-m-d")."' and 
				  golesEquipo1>-1 and golesEquipo2>-1
				  and f.idTorneoCat = ".$idTorneoCat;

		$datos = $db->getResults($query, ARRAY_A); 
		
		$db->close();

		return $datos;	

	}

	function getByEquipo($idTorneoCat,$idEquipo){

		$db = new Db();

       $query = "Select  x.* ,e1.nombre as equipo1,e2.nombre as equipo2
		          from ga_fixture x, ga_fechas f,
				  ga_equipos e1, ga_equipos e2 
				  where x.idFecha = f.id and
  				  x.idEquipo1 = e1.id and
				  x.idEquipo2 = e2.id and 
				  (x.idEquipo1 = ".$idEquipo." or x.idEquipo2 = ".$idEquipo.")   and 
				  f.idTorneoCat = ".$idTorneoCat."
				  order by idFecha";

		$datos = $db->getResults($query, ARRAY_A); 

		$db->close();

		return $datos;	

	}
	
	function partidoConfirmado($id_partido="", $id_equipo="") {
		
		$db = new Db();
	
		$query = "Select count(*) as cantidad from ga_partidos_confirmados where id_partido = $id_partido and id_equipo = $id_equipo";
		
		$res = $db->getRow($query); 
	
		$db->close();
		
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
		
	}
	
	function confirmarPartido($id_partido="",$id_equipo="") {
		
		$db = new Db();
		
		$today = date("Y-m-d");
	
		$query = "INSERT into ga_partidos_confirmados(id_partido,id_equipo,fecha_confirmado) VALUE (".$id_partido.",".$id_equipo.",'".$today."')";
		
		$res = $db->getRow($query); 
	
		$db->close();
		
	}
	
	function eliminarcConfirmacionPartido($id_partido="",$id_equipo="") {
		
		$db = new Db();
		
		$today = date("Y-m-d");
	
		$query = "DELETE FROM ga_partidos_confirmados WHERE id_partido = $id_partido and id_equipo = $id_equipo";
		
		$res = $db->getRow($query); 
	
		$db->close();
		
	}
	
	function jugaronEnContra( $id_equipo1="", $id_equipo2="" , $idTorneoCat = "", $idFecha = "") {
	
		$db = new Db();
	
		$query = "SELECT count(*) as cantidad FROM ga_fixture p,  ga_fechas f where p.idEquipo1 = $id_equipo1 and p.idEquipo2 = $id_equipo2 and p.idFecha = f.id and golesEquipo1>-1 and golesEquipo2>-1 and p.idFecha != $idFecha and f.idTorneoCat = $idTorneoCat;";
		
		$res = $db->getRow($query); 
	
		$db->close();
		
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
	
	}
	
	function resultadoPartido( $id_equipo1="", $id_equipo2="" , $idTorneoCat = "", $idFecha = "") {
		$db = new Db();
	
		$query = "SELECT p.golesEquipo1, p.golesEquipo2 FROM ga_fixture p,  ga_fechas f where p.idEquipo1 = $id_equipo1 and p.idEquipo2 = $id_equipo2 and p.idFecha = f.id and golesEquipo1>-1 and golesEquipo2>-1 and p.idFecha != $idFecha and f.idTorneoCat = $idTorneoCat;";
		
		$res = $db->getRow($query); 
	
		$datos = $db->getResults($query, ARRAY_A); 
	
		$db->close();
		
		return $datos;
	}
	
	function juegaEstaFecha( $id_equipo1="", $id_equipo2="" , $idTorneoCat = "", $idFecha = "") {
		
		$db = new Db();
	
		$query = "SELECT count(*) as cantidad FROM ga_fixture p,  ga_fechas f where p.idEquipo1 = $id_equipo1 and p.idEquipo2 = $id_equipo2 and p.idFecha = f.id  and p.idFecha = $idFecha and f.idTorneoCat = $idTorneoCat;";
		
		$res = $db->getRow($query); 
	
		$db->close();
		
		if($res->cantidad == 0) {
			return false;
		} else {
			return true;
		}
		
	}
	
	function getByFechaPartidoSede($fechaPartido, $idSede){

	   $db = new Db();

       $query = "Select  
	   				x.id as idPartido,
	   				x.horaPartido, 
					f.nombre as nombreFecha, 
					e1.nombre as equipo1,
					e1.id as id1,
					e2.nombre as equipo2,
					e2.id as id2,
					x.cancha as cancha,
					t.nombre as torneo, 
					c.nombrePagina as categoria,
					tc.id_padre as idzona
		          from 
				    ga_fixture x, 
					ga_fechas f, 
					ga_torneos_categorias tc, 
					ga_torneos t, 
					ga_equipos e1, 
					ga_equipos e2, 
					ga_categorias c
				  where 
				    x.fechaPartido = '$fechaPartido' and 
				    x.idSede = $idSede and
				    x.idFecha = f.id and
  				    x.idEquipo1 = e1.id and
				    x.idEquipo2 = e2.id and 
				    f.idTorneoCat = tc.id and
				    tc.id_torneo = t.id and
				    tc.id_categoria = c.id
				  order 
				    by x.horaPartido ASC";

		$datos = $db->getResults($query, ARRAY_A); 

		$db->close();

		return $datos;	

	}

}

?>