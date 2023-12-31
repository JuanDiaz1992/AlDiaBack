<?php


require_once "alDiaSettings/Connection.php";
class DeleteModel{

    //Metodo para eliminar usuario de la bd
    static public function deleteItemModel($data,$table){
        $sql = "DELETE FROM $table WHERE id = :id ";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":id", $data["id"], PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            return 200;
        } else {
            return 404;
        }
        
    }

    static public function deleteMenufromBdModel($table,$idMenu){
        $sql = "DELETE FROM $table WHERE id = :id";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":id", $idMenu, PDO::PARAM_INT);
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 200;
        } else {
            return 404;
        }
    }
}



?>