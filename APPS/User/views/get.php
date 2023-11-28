<?php
require_once "APPS/User/controller/get_controler.php";
require_once('alDiaSettings/Generator_token.php');
$response = new GetController();
$tokenDecode = Token::decodeToken($token);
if(isset($tokenDecode)){
    session_id($tokenDecode->id);
    session_start();
    if($table == "validateSession" ) {
        $response -> validateUSer($tokenDecode);
    }elseif($table === "profile"){
        $response->getDataProfileController($_GET);
    }
    else{
        if (isset($_GET["linkTo"]) && isset($_GET["equalTo"])) {
            $response -> getDataFilter($table,$select,$_GET["linkTo"],$_GET["equalTo"]);
        }else{
            $response->getData($table,$select);
        }
    }
}




?>