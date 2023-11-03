<?php

require_once "APPS/Financial_record/model/post_model.php";

class PostController{
    static public function record_income_or_expense_controller($data){
        error_log(print_r($data, true));
        if (
            $data['id_user'] == "" ||
            $data['date'] == "" ||
            $data['amount'] == "" ||
            $data['description'] == "" ||
            $data['category'] == ""
            ){
                PostController::fncResponse(409,'','Debes completar todos los campos');
                exit;
            }
        if(
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date']) || //En este if se validan caracteres especiales
            !preg_match('/^\d+(\.\d{1,2})?$/', $data['amount']) ||
            !preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/', $data['description'])){
                PostController::fncResponse(409,'','Los datos ingresados no son correctos, valide la información e intentelo de nuevo');
                exit;
            }
            $response = PostModel::record_income_or_expense_model($data);
            PostController::fncResponse($response,'','Registro ingresado correctamente');
    }

    //Respuesta del controlador:
    static public function fncResponse($status,$response = "",$message = ""){ //Metodo usado para dar respuestas básicas
        if ($status === 200) {
            $json = array(
                'status' => $status,
                'results' => 'success',
                'registered'=>true,
                'message' => $message
            );
        }else if($status === 409){
            $json = array(
                'registered'=>false,
                'status' => $status,
                'results' => 'Not Found',
                'message' => $message
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
