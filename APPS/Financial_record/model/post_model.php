<?php


require_once "alDiaSettings/Connection.php";
class PostModel{
    //Creación de Usuario nuevo 
    static public function record_income_model($data,$table){   
        $sql = "INSERT INTO $table (id_user, date, amount, description, category) VALUES (:id_user, :date, :amount, :description, :category)";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':id_user', $date);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':amount', $date);
        $stmt->bindParam(':description', $date);
        $stmt->bindParam(':category', $date);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if(isset($rowCount)){
            return 200;
        }else{
            return 400;
        }

    }

}



?>