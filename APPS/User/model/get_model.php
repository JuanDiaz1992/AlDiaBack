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
    static public function getDataProfileModel($GET,$table){
        $sql = "SELECT users.user, perfil.*, tipo_documento.tipo_documento
        FROM $table
        INNER JOIN perfil ON users.perfil = perfil.id_profile
        INNER JOIN tipo_documento ON perfil.tipo_documento = tipo_documento.id
        WHERE users.id = :id";
        $stmt = Connection::connect()->prepare($sql);
        $stmt->bindParam(":id", $GET["equalTo"], PDO::PARAM_STR);
        $stmt-> execute();
        return $stmt-> fetchAll(PDO::FETCH_CLASS);
    }




}
    



?>