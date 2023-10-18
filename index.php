<?php

require_once "alDiaSettings/cors.php";


//Manejo de errores
ini_set('display_errors',1);
ini_set('logs_errors',1);
ini_set('error_log','F:/xampp/htdocs/al_dia_backend/Error/php_error_log');

//Manejo de rutas
require_once "alDiaSettings/routes_controller.php";
$index = new RoutesController();
$index-> index();






?>