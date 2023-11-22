<?php
//Vista para solicitudes post del user

require_once "APPS/User/controller/post_controler.php";
require_once('alDiaSettings/Generator_token.php');
$response = new PostController();

if(isset($data["login_request"])){
    $table = "users";
    $response -> postDataconsultUser($table, $data["username"], $data["password"]);
    exit;
}else if(isset($data["newUser_request"])){
    $response ->postControllerCreateUser($data);
    exit;        
}else if(isset($data["complete_profile"])){
    $table = "users";
    $response ->postControllerCompleteRecord($data,$table);
    exit;
}

$tokenDecode = Token::decodeToken($token);
if(isset($_REQUEST["edit_user_request"])){
    session_id($tokenDecode->id);
    session_start();
    if($tokenDecode){
        $img = isset($_FILES['photo'])? $_FILES['photo'] : '';
        $response ->postControllerModify(
            $_POST['id'],
            $_POST['name'],
            $img,
            $_POST['type_user'],
            $_POST['username']
            );
    }
}else if(isset($data['changePasswordUser'])){
    session_id($tokenDecode->id);
    session_start();
    if($tokenDecode){
        $response ->changePassword(
            $data['id'],
            $data['password'],
            $data['confirmPassword'],
        );

    }
}else if(isset($_POST["chageProfilePhoto"])){
    session_id($tokenDecode->id);
    session_start();
    if($tokenDecode){
        $response ->postControllerModifyPhoto($_POST['id'],$_FILES['photo'],$_POST['user']);
    }
}
else{
    badResponse();
}





?>