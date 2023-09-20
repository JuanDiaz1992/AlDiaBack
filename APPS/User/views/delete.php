<?php

require_once "APPS/User/controller/delete_controler.php";
require_once('alDiaSettings/Generator_token.php');
$response = new DeleteController();
$tokenDecode = Token::decodeToken($token);
error_log(print_r($tokenDecode,true));
if (isset($tokenDecode)) {
    session_id($tokenDecode->id);
    session_start();
    if(isset($data["logout_request"])){
        $response -> logout($tokenDecode->id);

    }
    elseif(isset($data["delete_user"])){
        if($tokenDecode && $_SESSION["type_user"] === 'Admin'){
            $response -> deleteUserController($data["id"]);
        }else{
            badResponse();
        }
        
    }else if($tokenDecode === 400){
        badResponse();
    }
}

?>