<?php

require_once "APPS/Financial_record/model/post_model.php";

class PostController{
    static public function record_income_or_expense_controller($data,$photo){
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
            //En este bloque se valida si la solicitud viene con una imagen
            $rutaArchivoRelativa = null;
            if(isset($photo['name'])){
                $objetoTiempo = strtotime($data["date"]);
                $anioMesActual = date("Y-m",$objetoTiempo);
                $carpetaDestino = "files/user_profile/".$data["userName"]."/".$data['table']."/".$anioMesActual;
                $nombreArchivo = $photo['name'];
                $rutaArchivo = $carpetaDestino . "/" . $nombreArchivo;
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }
                $rutaArchivoRelativa = $carpetaDestino.'/'. $nombreArchivo;
                move_uploaded_file($photo['tmp_name'], $rutaArchivo);
            }
            $response = PostModel::record_income_or_expense_model($data,$rutaArchivoRelativa);
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
