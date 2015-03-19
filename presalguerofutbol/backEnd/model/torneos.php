<?PHP
include_once "include/_funciones.php";
include_once "mysql.class.php";

class Torneos {

	var $id;
	var $nombre;
	var $fechaInicio;
	var $fechaFin;
	var $logoPrincipal;
	var $logoMenu;
	var $orden;
	var $activo;
		
	function Torneos($id="") {

		if ($id != "") {
			$valores = $this->get($id);
			$this->id = $valores[0]["id"]; 
			$this->nombre = $valores[0]["nombre"];
			$this->nombre_pagina = $valores[0]["nombre_pagina"];			
			$this->fechaInicio = $valores[0]["fechaInicio"]; 
			$this->fechaFin = $valores[0]["fechaFin"];
			$this->logoPrincipal = $valores[0]["logoPrincipal"]; 
			$this->logoMenu = $valores[0]["logoMenu"];
			$this->orden = $valores[0]["orden"]; 
			$this->activo = ($valores[0]["activo"] == 'on')? 1: 0;
		}
	}

	
	function set($valores){
		$this->id = $valores["id"]; 
		$this->nombre = $valores["nombre"];
		$this->nombre_pagina = $valores["nombre_pagina"];			
		$this->fechaInicio = $valores["fechaInicio"]; 
		$this->fechaFin = $valores["fechaFin"];
		$this->logoPrincipal = $valores["logoPrincipal"]; 
		$this->logoMenu = $valores["logoMenu"];
		$this->orden = $valores["orden"]; 
		$this->activo =  ($valores["activo"] == 'on')? 1: 0;
	}
	
	function _setById($id) {	
		$aValores = $this->getById($id, ARRAY_A);	
		$this->set($aValores);
	}
		

	function insertar($files) {
		$db = new Db();
		$query = "select max( orden ) as orden from torneos";
		$max = $db->getRow($query); 
		$max_orden = $max->orden + 1;
		$this->fechaInicio = str_replace("/","-",$this->mysql_fecha($this->fechaInicio));
		$this->fechaFin = str_replace("/","-",$this->mysql_fecha($this->fechaFin));
		$query = "insert into torneos(nombre,nombre_pagina,fechaInicio,fechaFin,orden,activo) values (".
				"'".$this->nombre."',".
				"'".$this->nombre_pagina."',".				
				"'".$this->fechaInicio."',".
				"'".$this->fechaFin."',".
				"'".$max_orden."',".
				"'".$this->activo."')";
			
		$this->id = $db->query($query); 
		if(is_uploaded_file($_FILES['logoPrincipal']['tmp_name'])) {
			// Logo principal
			$name = "pri_".$this->id."_".$files['logoPrincipal']['name'];
			$ruta= "../logos/".$name;
			move_uploaded_file($_FILES['logoPrincipal']['tmp_name'], $ruta);
			$query = "update torneos set  logoPrincipal = '". $name."'
					  where id = ".$this->id ;
			$db->query($query);
		}
		if(is_uploaded_file($_FILES['logoMenu']['tmp_name'])) {
			// Logo Menu
			$name1 = "men_".$this->id."_".$files['logoMenu']['name'];
			$ruta= "../logos/".$name1;
			move_uploaded_file($_FILES['logoMenu']['tmp_name'], $ruta);
			$query = "update torneos set logoMenu = '". $name1."'		  
					  where id = ".$this->id ;
			$db->query($query); 
		}
		$db->close();
	}


	function eliminar() {
		$db = new Db();
		$query = "delete from torneos where id = ".$this->id ;
		$db->query($query); 
		$db->close();
	}
	
	function actualizar($files) {
		$db = new Db();
		$this->fechaInicio = str_replace("/","-",$this->mysql_fecha($this->fechaInicio));
		$this->fechaFin = str_replace("/","-",$this->mysql_fecha($this->fechaFin));
		$query = "update torneos set 
		          nombre = '". $this->nombre."',
		          nombre_pagina = '". $this->nombre_pagina."',
				  fechaInicio = '". $this->fechaInicio."',
		          fechaFin = '". $this->fechaFin."',				  
		          activo = '". $this->activo."'
				  where id = ".$this->id ;
		$db->query($query); 
		if(is_uploaded_file($_FILES['logoPrincipal']['tmp_name'])) {
			// Logo principal	
			$name = "pri_".$this->id."_".$_FILES['logoPrincipal']['name'];
			$ruta= "../logos/".$name;
			move_uploaded_file($_FILES['logoPrincipal']['tmp_name'], $ruta);
			$query = "update torneos set  logoPrincipal = '". $name."'
					  where id = ".$this->id ;
			$db->query($query); 
		}
		if(is_uploaded_file($_FILES['logoMenu']['tmp_name'])) {
			// Logo Menu
			$name1 = "men_".$this->id."_".$files['logoMenu']['name'];
			$ruta= "../logos/".$name1;	
			move_uploaded_file($_FILES['logoMenu']['tmp_name'], $ruta);
			$query = "update torneos set logoMenu = '". $name1."'		  
					  where id = ".$this->id ;
			$db->query($query); 
		}
		$db->close();
	}
	
	function get($id="") {
		$db = new Db();
		$query = "Select * from torneos" ;
		if ($id != "") {
			$query .= " where id = '$id' ";
		}
		$query .= " order by orden";	  
		$res = $db->getResults($query, ARRAY_A); 
		$db->close();
		return $res;
	}


	function getPaginado($filtros, $inicio, $cant, &$total) {
		$orden = ($filtros["filter_order"])?$filtros["filter_order"]:"p.nombre";
		$dir = ($filtros["filter_order_Dir"])?$filtros["filter_order_Dir"]:"asc"; 
		$db = new Db();
		$query = "Select SQL_CALC_FOUND_ROWS  p.*
		          From torneos p
				  where  1 = 1";
		if (trim($filtros["fnombre"]) != "")		 
			$query.= " and p.nombre like '%".strtoupper($filtros["fnombre"])."%'";		  
		$query.= " order by orden, $orden $dir LIMIT $inicio,$cant";
		$datos = $db->getResults($query, ARRAY_A); 
		$cant_reg = $db->getResults("SELECT FOUND_ROWS() cant", ARRAY_A); 
		$total = ceil( $cant_reg[0]["cant"] / $cant );
		$db->close();
		return $datos;	
	}

	function mysql_fecha($fech)	{
		$fech1= explode("/",$fech);
		if(strlen(trim($fech1[1]))==1){
			$fech1[1]="0".$fech1[1];
		}
		if(strlen(trim($fech1[0]))==1)	{
			$fech1[0]="0".$fech1[0];
		}
		return trim($fech1[2])."/".trim($fech1[1])."/".trim($fech1[0]);
	}

	function cambiarActivo($id,$valor)	{
		$db = new Db();
		$query = "update torneos set activo = '". $valor."' where id = ".$id ;
		$db->query($query); 
		$db->close();
	}

	function cambiarOrden($pos,$orden)	{
		$db = new Db();
		$nueva_pos = $pos+$orden;
		$query = "update torneos set orden = -100  where orden = ".$nueva_pos ;
		$db->query($query); 
		$query = "update torneos set orden = ". $nueva_pos."  where orden = ".$pos ;
		$db->query($query); 
		$query = "update torneos set orden = ". $pos."  where orden = -100" ;
		$db->query($query); 
		$db->close();
	}

	function getByCant($cant) {		
		$db = new Db();
		$query = "Select * From torneos  where activo = 1";
		$query .= " Order by orden LIMIT 0,$cant";
		$aTorneo = $db->getResults($query, ARRAY_A); 	
		$db->close();
		return $aTorneo;
	}		

	function getByTorneoCat($idTorneoCat) {	
		$db = new Db();
		$query = "Select *
				  From torneos  t, torneos_categorias tc where 
				  tc.id_torneo 	= t.id and  tc.id = ".$idTorneoCat;
		$oTorneo = $db->getRow($query);
		$db->close();
		return $oTorneo;
	}

}

?>