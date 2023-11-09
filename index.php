<?php
require_once "alDiaSettings/cors.php";


require "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
/*El bloque de código anterior es el encargado de traer las variables de entorno*/

ini_set('display_errors',1);
ini_set('logs_errors',1);

$os_info = php_uname();
if (stripos($os_info, 'linux') !== false) {
    ini_set('error_log','~/Documentos/htdocs/al_dia_backend/Error/php_error_log');
} elseif (stripos($os_info, 'windows') !== false) {
    ini_set('error_log','F:/xampp/htdocs/al_dia_backend/Error/php_error_log');
} else {
    echo 'No se pudo determinar el sistema operativo.';
}

//Manejo de errores



//Manejo de rutas
require_once "alDiaSettings/routes_controller.php";
$index = new RoutesController();
$index-> index();






?>