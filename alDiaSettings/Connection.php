<?php
class Connection{
    //Información de la base de datos
    static public function infoDatabase(){
        $infoDB = array(
            "database" => $_ENV["DATA_BASE"],
            "user"=> $_ENV["USER_DATA_BASE"],
            "pass" => $_ENV["PASSWORD_DATA_BASE"]
        );
        return $infoDB;
    }
    //Conexión a la bd
    static public function connect(){
        try{
            $link = new PDO(
                "mysql:host=localhost;dbname=".Connection::infoDatabase()["database"],
                Connection::infoDatabase()["user"],
                Connection::infoDatabase()["pass"],
            );
            $link->exec("set names utf8");
        }catch(PDOException $e){
            die("Error: ".$e->getMessage());

        }
        return $link;
    }

}



?>