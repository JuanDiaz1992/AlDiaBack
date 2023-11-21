<?php
require_once "APPS/Financial_record/controller/delete_controler.php";
require_once('alDiaSettings/Generator_token.php');
$response = new DeleteController();
$tokenDecode = Token::decodeToken($token);
if (isset($tokenDecode)) {
    session_id($tokenDecode->id);
    session_start();
    if(isset($data["deleteItem"])){
       $response -> deleteItemController($data);
    }
}else{
    badResponse();
}




?>