<?php
//Vista para solicitudes post del user

require_once "APPS/User/controller/post_controler.php";
$response = new PostController();
if(isset($data["login_request"])){
    $table = "users";
    $response -> postDataconsultUser($table, $data["username"], $data["password"]);
}else if(isset($data["newUser_request"])){
    $response ->postControllerCreateUser($data);        
}else if(isset($data["complete_profile"])){
    $table = "users";
    $response ->postControllerCompleteRecord($data,$table);
}else if(isset($_REQUEST["edit_user_request"])){
    session_id($token);
    session_start();
    if($token && $_SESSION["type_user"] === 'Admin'){
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
    session_id($token);
    session_start();
    if($token && $_SESSION["type_user"] === 'Admin'){
        $response ->changePassword(
            $data['id'],
            $data['password'],
            $data['confirmPassword'],
        );

    }
    
    
}else{
    badResponse();
}





?>