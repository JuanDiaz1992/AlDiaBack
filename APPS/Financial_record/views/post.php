<?php
//Vista para solicitudes post del user

require_once "APPS/Financial_record/controller/post_controler.php";
require_once('alDiaSettings/Generator_token.php');
$response = new PostController();
$tokenDecode = Token::decodeToken($token);
if(isset($tokenDecode)){
    session_id($tokenDecode->id);
    session_start();
    if(isset($data["record_income"])){
        $response -> record_income_controller($data);
    }
}else{
    badResponse();
}






?>