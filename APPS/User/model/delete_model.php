<?php


require_once "alDiaSettings/Connection.php";
class DeleteModel{
    //Peticiones post sin filtro
    static public function deleteData($table, $select){
        $sql = "SELECT $select FROM $table";
        $stmt = Connection::connect()->prepare($sql);
        $stmt-> execute();
        return $stmt-> fetchAll(PDO::FETCH_CLASS);
    }
    //Metodo para eliminar usuario de la bd
    static public function deleteUserModel($id){
        $sql = "DELETE FROM profile_user WHERE id = :id ";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            return 200;
        } else {
            return 404;
        }
        
    }
    static public function deleteUserPhotoModel($id,$blankRoute){
        $sql = "UPDATE perfil 
        JOIN users ON perfil.id_profile = users.perfil
        SET foto_perfil = :foto_perfil
        WHERE users.id = :id";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->bindParam(":foto_perfil", $blankRoute, PDO::PARAM_STR);
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