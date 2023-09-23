<?php


require_once "APPS/User/model/get_model.php";

class GetController{
    static public function getData($table,$select){
        $response = GetModel::getData($table,$select);
        $return = new GetController();
        $return -> fncResponse($response);


    }
    static public function getDataFilter($table,$select,$linkTo,$equalTo){
        $response = GetModel::getDataFilter($table,$select,$linkTo,$equalTo);
        $return = new GetController();
        $return -> fncResponse($response);



    }

    static public function getAllUsers($table,$select){
        
        $response = GetModel::getAllUsers($table,$select);
        $return = new GetController();
        $consultUsers = true;

        $return -> fncResponse($response,$consultUsers);
        
    }


    static public function validateUSer($tokenOK){
        $return = new GetController();
        $response = "";
        if (isset($tokenOK)) {
            $response = array(
                'status' => 200,
                'logged_in' => true,
            );
        }
        $return -> fncResponse($response);  
    }

    
    //Respuesta del controlador:
    public function fncResponse($response, $consultUsers=false){
        if (!empty($response)&&$consultUsers ===false) {
            $json = array(
                'status' => 200,
                'total' => count($response),
                'results' => $response
                
            );
        }elseif(!empty($response) && $consultUsers) {
            $users = array();
            foreach($response as $key => $value){
                $user = array(
                    'id' => $value->id,
                    'username' => $value->username,
                    'name' => $value->name,
                    'photo' => $value->photo,
                    'type_user' => $value->type_user,
                );
                array_push($users, $user);
            }
            $json = array(
                'status' => 200,
                'total' => count($response),
                'users' => $users
 
            );
        
        }
        else{
            $json = array(
                'status' => 404,
                'results' => 'Not Found'
            );
        }
        echo json_encode($json,http_response_code($json['status']));

    
    }
}


?>