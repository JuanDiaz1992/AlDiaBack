<?php


require_once "alDiaSettings/Connection.php";
class PostModel{
    //Creación de Usuario nuevo 
    static public function record_income_or_expense_model($data){   
        $table = $data["table"];
        $sql = "INSERT INTO $table (id_user, date, amount, description, category) VALUES (:id_user, :date, :amount, :description, :category)";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':id_user', $data["id_user"]);
        $stmt->bindParam(':date', $data["date"]);
        $stmt->bindParam(':amount', $data["amount"]);
        $stmt->bindParam(':description', $data["description"]);
        $stmt->bindParam(':category', $data["category"]);
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