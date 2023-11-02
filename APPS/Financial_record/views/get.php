<?php
require_once "APPS/Financial_record/controller/get_controler.php";
require_once('alDiaSettings/Generator_token.php');
$response = new GetController();
$tokenDecode = Token::decodeToken($token);
if(isset($tokenDecode)){
    session_id($tokenDecode->id);
    session_start();
    $response->getData($table,$select);   
}else{
    badResponse();
}




?>