<?php


require_once "alDiaSettings/Connection.php";
class PostModel{
    //Creación de Usuario nuevo 
    static public function postDataCreateUser($data,$hashedPassword,$type_user){   
        //Valida si el correo o el username ya existe
        $sql = "SELECT * FROM perfil WHERE correo = :correo";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":correo", $data["email"], PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        if($row){
            return [
                "code" =>409
            ];
        }
        
        $sql = "SELECT * FROM users WHERE user = :user";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":user", $data["userName"], PDO::PARAM_STR);
        $stmt->execute();
        $row2 = $stmt->fetch();
        if ($row2) {
            return [
                "code" =>409
            ];
        }

        //Se crea el perfil
        $sql = "INSERT INTO perfil (primer_nombre,segundo_nombre,primer_apellido,segundo_apellido,correo) VALUES (:primer_nombre,:segundo_nombre,:primer_apellido,:segundo_apellido,:correo )";
        $stmt = Connection::connect();
        $stmtFull = $stmt->prepare($sql);
        $stmtFull->bindParam(':primer_nombre', $data["firstName"]);
        $stmtFull->bindParam(':segundo_nombre', $data["secondName"]);
        $stmtFull->bindParam(':primer_apellido', $data["firstLastName"]);
        $stmtFull->bindParam(':segundo_apellido',$data["secondLastName"]);
        $stmtFull->bindParam(':correo',$data["email"]);
        $stmtFull->execute();
        $rowCount = $stmtFull->rowCount();
        $idPerfil = $stmt->lastInsertId(); // Obtener el ID generado

        //se crea el usuario y la contraseña, con el tipo de usuario y se añade el id de perfil
        $sql = "INSERT INTO users (user, password, perfil, type_user) VALUES (:user, :password, :perfil, :type_user)";
        $stmt = Connection::connect();
        $stmtFull = $stmt->prepare($sql);
        $stmtFull->bindParam(':user', $data["userName"]);
        $stmtFull->bindParam(':password', $hashedPassword);
        $stmtFull->bindParam(':perfil', $idPerfil);
        $stmtFull->bindParam(':type_user', $type_user);
        $stmtFull->execute();
        $rowCount = $stmtFull->rowCount();
        if ($rowCount > 0){
            return [
                "idPerfil"=>$idPerfil,
                "code" =>200
            ];
        }else{
            return [
                "code" =>409
            ];
        }


    }

    //Función para agregar los datos restantes del registro de usuario
    static public function postDataCompleteRecord($data){
        $sql = "UPDATE perfil SET 
                tipo_documento = :tipo_documento, 
                numero_documento = :numero_documento, 
                fecha_nacimiento = :fecha_nacimiento,
                departamento = :departamento,
                municipio = :municipio,
                direccion = :direccion,
                telefono = :telefono,
                estado_civil = :estado_civil,
                ocupacion = :ocupacion,
                tratamiento_datos = :tratamiento_datos
                WHERE id_profile = :id_profile";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":tipo_documento", $data["typeDocument"], PDO::PARAM_STR);
        $stmt->bindParam(":numero_documento", $data["document"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_nacimiento", $data["birthdate"], PDO::PARAM_STR);
        $stmt->bindParam(":departamento", $data["departamentSelect"], PDO::PARAM_STR);
        $stmt->bindParam(":municipio", $data["citiSelecte"], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $data["address"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $data["phone"], PDO::PARAM_STR);
        $stmt->bindParam(":estado_civil", $data["civilState"], PDO::PARAM_STR);
        $stmt->bindParam(":ocupacion", $data["occupation"], PDO::PARAM_STR);
        $stmt->bindParam(":tratamiento_datos", $data["dataPolitic"], PDO::PARAM_STR);
        $stmt->bindParam(":id_profile", $data["idsPerfil"], PDO::PARAM_STR);
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
        INNER JOIN perfil ON users.perfil = perfil.id_profile 
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



    static public function PostDataModifyPhoto($id,$photo){   
        $sql = "UPDATE perfil 
        JOIN users ON perfil.id_profile = users.perfil
        SET foto_perfil = :foto_perfil
        WHERE users.id = :id";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->bindParam(":foto_perfil", $photo, PDO::PARAM_STR);
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