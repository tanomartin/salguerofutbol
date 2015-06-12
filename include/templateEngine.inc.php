<?php

// Preparando el engine de templates
// Twig

// Incluimos Twig Auto Loader
$root = '';
require($root . 'lib/php/Twig/Autoloader.php');
Twig_Autoloader::register();

// Definimos la ruta donde estarán nuestros templates
$loader = new Twig_Loader_Filesystem($root . 'formularios');

// Inicializamos twig
$twig = new Twig_Environment($loader);

?>