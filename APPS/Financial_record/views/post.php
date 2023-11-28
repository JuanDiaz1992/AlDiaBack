<?php
//Vista para solicitudes post del user

require_once "APPS/Financial_record/controller/post_controler.php";
require_once('alDiaSettings/Generator_token.php');
$response = new PostController();
$tokenDecode = Token::decodeToken($token);
if(isset($tokenDecode)){
    session_id($tokenDecode->id);
    session_start();
    if(isset($_POST["record_income"])){
        $img = isset($_FILES['photo'])? $_FILES['photo'] : '';
        error_log(print_r($_POST,true));
        $response -> record_income_or_expense_controller($_POST,$img);
    }
}else{
    badResponse();
}






?>