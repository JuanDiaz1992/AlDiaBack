<?php
require_once "APPS/Inventory/controller/get_controler.php";
$response = new GetController();

if(isset($token)){
    session_id($token);
    session_start();
    
    if(isset($_SESSION["estatus"]) == true){
        if($table == 'inventoryBuysForDate'){
            $response -> getInventoryForDate("buys",$_GET["linkTo"],$_GET["equalTo"]);
        }
        else if($table == 'inventoryBillsForDate'){
            $response -> getInventoryForDate("bills",$_GET["linkTo"],$_GET["equalTo"]);
        }
        else if($table == 'items_menu'){
            $response -> getData($table,$select);
        }
        else if($table =='items_menu_temp'){
            $response ->getDataBySession();
        }
        else{
            //Aqui validamos si la consulta es de tipo where, sino es una consulta a toda la tabla 
            if (isset($_GET["linkTo"]) && isset($_GET["equalTo"])) {
                $response -> getDataFilter($table,$select,$_GET["linkTo"],$_GET["equalTo"]);
        
            }else{
                $response->getData($table,$select);
            }        
        }
    }else{
        badResponse();
    }
    
}




?>