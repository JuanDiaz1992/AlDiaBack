<?php

require_once "alDiaSettings/Connection.php";
$response = new GetController();
class GetModel{
    //Peticiones get sin filtro
    static public function getData($table, $select){

        $sql = "SELECT $select FROM $table";
        $stmt = Connection::connect()->prepare($sql);
        $stmt-> execute();
        return $stmt-> fetchAll(PDO::FETCH_CLASS);
    }
    //Peticiones get con filtro
    static public function getDataFilter($table,$select,$linkTo,$equalTo){
        $linkToArray = explode(",",$linkTo);
        $equalToArray = explode("_",$equalTo);
        $linkToText = "";

        if (count($linkToArray)>1){
            foreach($linkToArray as $key => $value){
                if($key > 0){
                    $linkToText .= "AND ".$value." = :".$value." ";                }
            }
        }                
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";
        $stmt = Connection::connect()->prepare($sql);
        foreach($linkToArray as $key => $value){
            $stmt -> bindParam(":".$value,$equalToArray[$key],PDO::PARAM_STR);
        }

        $stmt -> execute();
        return $stmt -> fetchAll(PDO::FETCH_CLASS);

    }

    static public function getData_state_financial_model($GET,$table){
        $sql="SELECT * FROM $table WHERE id_user = :id_user AND DATE_FORMAT(date, '%Y-%m') = :date";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':id_user', $GET["equalTo"]);
        $stmt->bindParam(':date', $GET["dateTo"]);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
    static public function getData_state_financial_model_join($GET,$table,$category){
        $sql="SELECT $table.*,$category.name_category AS name_category FROM $table JOIN $category ON $table.category = $category.id WHERE id_user = :id_user AND DATE_FORMAT(date, '%Y-%m') = :date";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':id_user', $GET["equalTo"]);
        $stmt->bindParam(':date', $GET["dateTo"]);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
    

    static public function getDataWithJoin($table, $select, $linkTo, $equalTo){
        $sql = "SELECT * FROM $table JOIN all_menus ON items_menu.id = all_menus.contenido WHERE all_menus.menu = :menu";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':menu', $equalTo);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
    
    

}
    



?>