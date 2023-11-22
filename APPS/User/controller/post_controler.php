<?php

require_once "APPS/User/model/post_model.php";
require_once('vendor/autoload.php');
use Firebase\JWT\JWT;

class PostController{


    /************************Metodo para crear usuarios nuevos *********************/
    static public function postControllerCreateUser($data){

        if  (
                $data['firstName'] == "" ||
                $data['firstLastName'] == "" ||
                $data['userName'] == "" ||
                $data['password'] == "" ||
                $data['confirmPasword'] == "" ||
                $data['email'] == ""
            ){
                $json = array(
                    'status' => 409,
                    'message' => 'Ningun campo puede estar vacío, excepto segundo nombre o segundo apellido '
                );
                echo json_encode($json, http_response_code($json['status']));
                exit;

            }
        if (!preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ]+$/', $data['firstName']) || //En este if se validan caracteres especiales
            !preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/', $data['secondName']) ||
            !preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ]+$/', $data['firstLastName']) ||
            !preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/', $data['secondLastName']) ||
            !preg_match('/^[a-zA-Z0-9]+$/', $data['userName']) ||
            !preg_match('/^[a-zA-Z0-9]+$/', $data['password']) ||
            !preg_match('/^[a-zA-Z0-9]+$/',$data['confirmPasword']) ||
            $data['confirmPasword'] != $data['password']
            ){
                $json = array(
                    'status' => 409,
                    'message' => 'Los datos ingresados no son correctos, valide la información e intentelo de nuevo'
                );
                echo json_encode($json, http_response_code($json['status']));
                exit;
            }
        if  (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $json = array(
                'status' => 409,
                'message' => 'El correo electrónico no es válido'
            );
            echo json_encode($json, http_response_code($json['status']));
            exit;
            }

        $type_user = 2;
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT); //Aquí se genera un hash para la contraseña
        $response = PostModel::postDataCreateUser($data,$hashedPassword,$type_user);
        $return = new PostController();
        if ($response["code"] == 409){
            $return -> fncResponse($response,409);
        }elseif($response["code"] == 200){
            $return -> fncResponse($response,200);
        }
        
    }

    //Función que completa el registro del usuario.
    static public function postControllerCompleteRecord($data,$table){
        if (!preg_match('/^[0-9]+$/', $data["typeDocument"]) ||
            !preg_match('/^[0-9_-]+$/', $data["document"]) ||
            !preg_match('/^[0-9_-]+$/', $data["birthdate"]) ||
            !preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/', $data["departamentSelect"]) ||
            !preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/', $data["citiSelecte"]) ||
            !preg_match('/^[a-zA-Z0-9_#áéíóúÁÉÍÓÚñÑ\s-]+$/', $data["address"]) ||
            !preg_match('/^[0-9_#\s+-]+$/', $data["phone"]) ||
            !preg_match('/^[a-zA-Z0-9_#áéíóúÁÉÍÓÚñÑ\s-]+$/', $data["occupation"]) ||
            !is_bool($data["dataPolitic"])){
                $json = array(
                    'status' => 404,
                    'is_logged_in' => false,
                    'message' => 'Los datos no pueden contener caracteres especiales'
                );
                echo json_encode($json, http_response_code($json['status']));
                exit;
            }
        $response = PostModel::postDataCompleteRecord($data);
        $return = new PostController();
        if ($response == 404){
            $return -> fncResponse($response,404);

        }elseif($response == 200){
            $return ->postDataconsultUser($table,$data["userName"],$data["password"]);
        }
    }
    //***********************Este metodo es usado para el inicio de sessión***************/
    static public function postDataconsultUser($table,$username,$password){ 

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username) || !preg_match('/^[a-zA-Z0-9]+$/', $password)) { //Si el usuario o contraseña incluyen caracteres, no permite continúar
            $json = array(
                'status' => 404,
                'is_logged_in' => false,
                'message' => 'El usuario o la contraseña no pueden contener caracteres especiales.' 
            );
            echo json_encode($json,http_response_code($json['status']));

        }else{//Si no hay caracteres especiales se envía la información al modelo para validar si el usuario y la contraseña coinciden
            $response = PostModel::postDataconsultUser($table,$username,$password);
            $return = new PostController();
            $return -> isUserOk($response);
        }


    }
    static public function postControllerModifyPhoto($id,$photo,$userName){     
            if(isset($photo['name'])){ //Si el formulario incluye una imagen, la agrega, sino se pone la img por defecto
                $carpetaDestino = "files/user_profile/" . $userName;
                $nombreArchivo = $photo['name'];
                $rutaArchivo = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }
                $rutaArchivoRelativa = 'files/user_profile/' . $userName .'/'. $nombreArchivo;
                move_uploaded_file($photo['tmp_name'], $rutaArchivo);
            }else{
                $json = array(
                    'status' => 404,
                    'message' => 'Error al cargar la imagen'
                );
                echo json_encode($json, http_response_code($json['status']));
                exit;
            }
            $response = PostModel::PostDataModifyPhoto($id,$rutaArchivoRelativa);
            $return = new PostController();
            if ($response == 409){
                $return -> fncResponse($response,409);

            }elseif($response == 200){
                $return -> fncResponse($response,200);
            }
          
    }
    static public function changePassword($id,$password,$confirmPassword){
        if (!preg_match('/^[a-zA-Z0-9]+$/', $password) ||
        !preg_match('/^[a-zA-Z0-9]+$/', $confirmPassword)) {
            $json = array(
                'status' => 404,
                'message' => 'Los datos no pueden contener caracteres especiales'
            );
            echo json_encode($json, http_response_code($json['status']));
            exit;
        }

        if ($password !== $confirmPassword) { //Aquí se valida que la contraseña sea correcta 
            $json = array(
                'status' => 404,
                'is_logged_in' => false,
                'message' => 'Las contraseñas no coinciden'
            );
            echo json_encode($json, http_response_code($json['status']));
            exit;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); //Aquí se genera un hash para la contraseña
            $response = PostModel::PostChagePassword($id,$hashedPassword);
            $return = new PostController();
            if ($response == 404){
                $return -> fncResponse($response,404);

            }elseif($response == 200){
                $return -> fncResponse($response,200);
            }
    }
    public function isUserOk($response){ //Si el usuario y contraseña coinciden, se inicia sesión
        if (!empty($response)) {
            require_once "alDiaSettings/Generator_token.php";
            $tokenGerator = Token::generateToken($response[0]->id, $response[0]->user);
            $jwt = JWT::encode($tokenGerator,'3aw58420', 'HS256'); //Generación de token con los datos de usuario y codigo alfanumerico
            session_id($tokenGerator["id"]);
            session_set_cookie_params(1800);
            session_start();
            $_SESSION["exp"] = $tokenGerator["exp"]; //Aquí se expesifica la fecha de expiración
            $_SESSION["username"] = $response[0]->user; //Se guardan las variables de sesión
            $_SESSION["estatus"] = true;
            $_SESSION["type_user"] = $response[0]->type_user;
            $json = array( //Se devuelve el json con la información necesaria para inicia la sesión en el front
                'status' => 200,
                'is_logged_in' => true, 
                'token' => $jwt,
                'username'=> $_SESSION["username"],
                "message"=> "Usuario correcto",
                "firtsName"=> $response[0]->primer_nombre,
                "middleName"=>$response[0]->segundo_nombre,
                "last_name"=>$response[0]->primer_apellido,
                "second_last_name"=>$response[0]->segundo_apellido,
                "type_user" => $response[0]->type_user,
                "photo" => $response[0]->foto_perfil,
                "id_user"=>$response[0]->id
            );
        }else{
            $json = array( //Si la contraseña o el usuario son incorrectos, se devuelve la respuesta 
                'status' => 404,
                'is_logged_in' => false,
                'message' => 'User or password incorrect' 
            );
        }
        echo json_encode($json,http_response_code($json['status']));

    } 
    
    //Respuesta del controlador:
    public function fncResponse($response,$status){ //Metodo usado para dar respuestas básicas
        if (!empty($response) && $status === 200) {
            $json = array(
                'status' => $status,
                'results' => $response,
                'registered'=>true,
                'message' => "Registro ingresado correctamente" 
            );
        }else if($status === 409){
            $json = array(
                'registered'=>false,
                'status' => $status,
                'results' => 'Not Found',
                'message' => "El usuario o correo ya existe"
            );
        }else{
            $json = array(
                'registered'=>false,
                'status' => 404,
                'results' => 'Not Found',
                'message' => "No se pudo realizar el registro, valide los datos e intentelo de nuevo"
            );
        }

        echo json_encode($json,http_response_code($json['status']));

    
    }


}


?>