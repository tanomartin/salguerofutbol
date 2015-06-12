<? session_start();
    header('Content-Type:text/html; charset=utf-8');

	include_once "./include/_funciones.php";

	define( 'DB_SERVER','localhost');
	define( 'DB_SERVER_USERNAME','230787-martin');
    define( 'DB_SERVER_PASSWORD','andromeda');
	define( 'DB_DATABASE','230787_presalguero');
	// Defino constantes
	define("_DB1_","mysql");
	define("DIR_INC","../model/");
	define("UPLOAD_DIR","/upload/");
	define("CLASS_MODEL_","../model/");

	define( 'DIR_INCLUDES',"include/");
?>