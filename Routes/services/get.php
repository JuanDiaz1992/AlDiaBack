<?php

$table = explode("?",$routesArray[2])[0];
$select = $_GET["select"]??"*";
function badResponse(){
    $json = array(
        'status' => 404,
        'results' => 'Not Found'
    );

    echo json_encode($json,http_response_code($json['status']));

}

$token = "";

if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
    if (strpos($authorizationHeader, 'Token') === 0) {
        // Obtener el valor del token eliminando 'Token ' del encabezado
        $token = substr($authorizationHeader, 6);
    }
    $username = isset($_SERVER['HTTP_X_USERNAME']) ? $_SERVER['HTTP_X_USERNAME'] : "";
}
if (isset($_SERVER['HTTP_MODULE'])) {
    if(isset($_SERVER['HTTP_MODULE'])){
        $module = $_SERVER['HTTP_MODULE'];
    }
    else{
        badResponse();
    }
}


if($module == 'user'){
    require_once "APPS/User/views/get.php";  
}else if($module == 'business'){
    require_once "APPS/Business/views/get.php";
}else if($module == 'inventory'){
    require_once "APPS/Inventory/views/get.php";
}else if($module == 'menu_management'){
    require_once "APPS/Menu_management/views/get.php";
}

?>