<?php
require_once "APPS/Financial_record/controller/delete_controler.php";
$response = new DeleteController();

session_id($token);
session_start();
if($token && $_SESSION["type_user"] === 'Admin'){
    if(isset($data["delete_item_data"])){
        $response -> deleteItemTemporal($data["idItemMenu"]);
    }
    else if (isset($data["delete_item_bd_from_menu"])) {
        $table = "items_menu";
        $response -> deleteItemFromMenuBd($table, $data["item"]);
    }else if(isset($data["delete_item_menu_bd"]) && $data["delete_item_menu_bd"] == 1){       
        $table = "all_menus";
        $response -> deleteItemFromMenuBd($table, $data["id"]);
    }else if(isset($data["delete_all_menu"])&& $data["delete_all_menu"] == 1){
        $table = "menu";
        $response -> deleteMenufromBd($table,$data["idMenu"]);
    }else if(isset($data["delete_menu"])){
        $table = "menu";
        $response -> deleteMenufromBd($table,$data["idMenu"]);
    }
}else{
    badResponse();
}

?>