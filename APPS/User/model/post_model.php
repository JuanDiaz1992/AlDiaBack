<?php


require_once "alDiaSettings/Connection.php";
class PostModel{
    //Creación de Usuario nuevo 
    static public function postDataCreateUser($firstName,$secondName,$firstLastName,$secondLastName,$userName,$email,$hashedPassword){   

        //Valida si el correo o el username ya existe
        $sql = "SELECT * FROM informacion_complementaria WHERE correo = :correo";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":correo", $email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        if($row){
            return 409;
        }
        
        $sql = "SELECT * FROM users WHERE user = :user";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":user", $userName, PDO::PARAM_STR);
        $stmt->execute();
        $row2 = $stmt->fetch();
        if ($row2) {
            return 409;
        }

        //Crea el id para información complementaria y registra el correo, el id se requiere para crear el perfil
        $sql = "INSERT INTO informacion_complementaria (correo) VALUES (:correo)";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':correo', $email);
        $stmt->execute();
        $idInformacionComplementaria = $stmt->lastInsertId(); // Obtener el ID generado
        error_log($idInformacionComplementaria);
        //Se crea el perfil y se añade el id de información complementaria
        $sql = "INSERT INTO perfil (primer_nombre,segundo_nombre,primer_apellido,segundo_apellido,informacion_complementaria) VALUES (:primer_nombre,:segundo_nombre,:primer_apellido,:segundo_apellido,:informacion_complementaria )";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':primer_nombre', $firstName);
        $stmt->bindParam(':segundo_nombre', $secondName);
        $stmt->bindParam(':primer_apellido', $firstLastName);
        $stmt->bindParam(':segundo_apellido', $secondLastName);
        $stmt->bindParam(':informacion_complementaria', $idInformacionComplementaria);
        $stmt->execute();
        $idPerfil = Connection::connect()->lastInsertId(); // Obtener el ID generado

        //se crea el usuario y la contraseña, con el tipo de usuario y se añade el id de perfil
        $sql = "INSERT INTO users (user, password, perfil,type_user) VALUES (:user, :password, :perfil, :type_user)";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(':user', $userName);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':perfil', $idPerfil);
        $stmt->bindParam(':type_user', 2);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0){
            return 200;
        }else{
            return 404;
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