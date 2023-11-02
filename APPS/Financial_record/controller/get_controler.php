<?php


require_once "APPS/Financial_record/model/get_model.php";

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


    static public function getDataBySession(){
        $response = $_SESSION["menu_temp"];
        // Arreglos para cada tipo de menú
        $especialities = [];
        $soups = [];
        $beginning = [];
        $meats = [];
        $drinks = [];
        // Clasifica los elementos en los arreglos correspondientes
        foreach ($response as $element) {
            switch ($element['menu_item_type']) {
                case 'especialities':
                    $especialities[] = $element;
                    break;
                case 'soups':
                    $soups[] = $element;
                    break;
                case 'beginning':
                    $beginning[] = $element;
                    break;
                case 'meats':
                    $meats[] = $element;
                    break;
                case 'drinks':
                    $drinks[] = $element;
                    break;
            }
        }
        // Combina los arreglos en el orden deseado
        $orderedResponse = array_merge($especialities, $soups, $beginning, $meats, $drinks);
        $_SESSION["menu_temp"] = $orderedResponse;
        // Registra el array ordenado en el archivo de registro de errores
        // error_log(print_r($orderedResponse, true));
        $return = new GetController();
        $return -> fncResponse($orderedResponse);
    }

    
    static public function getDataWithJoin($table, $select, $linkTo, $equalTo){
        $response = GetModel::getDataWithJoin($table, $select, $linkTo, $equalTo);
        $return = new GetController();
        $return->fncResponse($response);
    }
    

    
    //Respuesta del controlador:
    public function fncResponse($response, $consultUsers=false){
        if (!empty($response)&&$consultUsers ===false) {
            $json = array(
                'status' => 200,
                'total' => count($response),
                'results' => $response
                
            );        
        }else{
            $json = array(
                'status' => 404,
                'results' => 'Not Found'
            );
        }
        echo json_encode($json,http_response_code($json['status']));

    
    }
}


?>