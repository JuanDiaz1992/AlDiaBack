<?php

require_once "APPS/User/model/delete_model.php";


class DeleteController{
    static public function deleteData($table,$select){
        $response = DeleteModel::deleteData($table,$select);
        $return = new DeleteController();
        $return -> fncResponse($response);


    }
    static public function logout($id_session){
        session_destroy();
        session_unset();
        $json = array(
            'status' => 200,
            'is_logged_in' => false,
            'message'=>'Usuario deslogueado'
        );
        echo json_encode($json,http_response_code($json['status']));
    }

    static public function deleteUserController($id){
        $response = DeleteModel::deleteUserModel($id);
        $return = new DeleteController();
        $return -> fncResponse($response);
    }


    //Respuesta del controlador:
    public function fncResponse($response){
        if ($response === 200) {
            $json = array(
                'status' => 200,
                'message' => 'Usuario eliminado con exito'
            );
        }else if($response === 404){
            $json = array(
                'status' => 404,
                'message' => 'Ocurrió un error al eliminar el usuario',
                'results' => 'Not Found'
            );
        }
        echo json_encode($json,http_response_code($json['status']));

    
    }
}


?>