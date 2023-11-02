<?php

require_once "APPS/Menu_management/model/delete_model.php";


class DeleteController{

    static public function deleteItemFromMenuBd($table,$id){
        $response = DeleteModel::deleteItemFromMenuBdModel($table,$id);
        $return = new DeleteController();
        $return -> deleteIfromSesion($id);
        $return -> fncResponse($response);
    }

    static public function deleteMenufromBd($table,$idMenu){
        $response = DeleteModel::deleteMenufromBdModel($table,$idMenu);
        $return = new DeleteController();
        $return -> fncResponse($response);
    }
    static public function deleteItemTemporal($id){
        $return = new DeleteController();
        $result = $return -> deleteIfromSesion($id);
        if ($result === 200) {
            $return -> fncResponse($result);
        }
    }
    
    static public function deleteIfromSesion($idItemMenu){
        $return = new DeleteController();
        $tempArray = array();
        foreach($_SESSION["menu_temp"] as  $key => $existingItem){
            if($existingItem["id"]!=$idItemMenu){
                array_push($tempArray, $existingItem);
            }
        }
        unset($_SESSION["menu_temp"]);
        $_SESSION["menu_temp"] = $tempArray;
        return 200;


    }


    //Respuesta del controlador:
    public function fncResponse($response){
        if ($response === 200) {
            $json = array(
                'status' => 200,
                'message' => 'Dato eliminado con exito'
            );
        }else if($response === 404){
            $json = array(
                'status' => 404,
                'message' => 'Ocurrió un error al eliminar el dato',
                'results' => 'Not Found'
            );
        }
        echo json_encode($json,http_response_code($json['status']));

    
    }
}


?>