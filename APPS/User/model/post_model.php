<?php


require_once "alDiaSettings/Connection.php";
class PostModel{
    //Creación de Usuario nuevo 
    static public function postDataCreateUser($id_business,$userName, $password, $name, $photo, $type_user)
    {   
        $sql = "SELECT * FROM profile_user WHERE username = :username";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":username", $userName, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        if($row){
            return 409;
        }else{
            $sql = "INSERT INTO profile_user (username, password, name, photo, type_user, id_negocio) VALUES (:username, :password, :name, :photo, :type_user, :id_negocio)";
            $stmt = Connection::connect()->prepare($sql);
            
            $stmt->bindParam(':username', $userName);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':photo', $photo);
            $stmt->bindParam(':type_user', $type_user);
            $stmt->bindParam(':id_negocio', $id_business);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            return 200;
        }

    }

    //Este metodo es utilizado para consultar contraseña con el has
    static public function postDataconsultUser($table, $user, $password)
    {
        $sql = "SELECT users.*, perfil.* 
        FROM users
        INNER JOIN perfil ON users.perfil = perfil.id 
        WHERE users.user = :user";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":user", $user, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS);

        // Verificar si se encontró un usuario con el nombre proporcionado
        if (count($result) > 0) {
            $hashedPassword = $result[0]->password;

            // Verificar si la contraseña ingresada coincide con el hash de la contraseña almacenada
            if (password_verify($password, $hashedPassword)) {
                // La contraseña es válida
                return $result;
            } else {
                // La contraseña es inválida
                return false;
            }
        } else {
            // No se encontró un usuario con el nombre proporcionado
            return false;
        }
    }



    static public function PostDataModify($id,$name,$photo,$type_user)
    {   if($photo){
            $sql = "UPDATE profile_user SET photo = :photo, name = :name, type_user = :type_user WHERE id = :id";
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":photo", $photo, PDO::PARAM_STR);
        }else{
            $sql = "UPDATE profile_user SET name = :name, type_user = :type_user WHERE id = :id";
            $stmt = Connection::connect()->prepare($sql);
        }
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":type_user", $type_user, PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0){
            return 200;
        }else{
            return 404;
        }
        
    }

    static public function PostChagePassword($id,$password){
        $sql = "UPDATE profile_user SET password = :password WHERE id = :id";
        $stmt = Connection::connect()->prepare($sql);
        
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0){
            return 200;
        }else{
            return 404;
        }
    }


//Este metodo es utilizado para consultar contraseña sin el has, 
//se debe usar en caso de que no exista un usuario Admin y sea necesario crear un Admin desde la bd

    // static public function postDataconsultUser($table,$user,$password){
    //     $sql = "SELECT * FROM $table WHERE username = :username AND password = :password";
    //     $stmt = Connection::connect()->prepare($sql);
    //     $stmt->bindParam(":username", $user, PDO::PARAM_STR);
    //     $stmt->bindParam(":password", $password, PDO::PARAM_STR);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_CLASS);
    // }
}



?>