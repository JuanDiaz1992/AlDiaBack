<?php
require_once "APPS/Financial_record/controller/get_controler.php";
require_once('alDiaSettings/Generator_token.php');
$response = new GetController();
$tokenDecode = Token::decodeToken($token);
if(isset($tokenDecode)){
    session_id($tokenDecode->id);
    session_start();
    if($table === "setStateFinancial" ){
        $response->getData_state_financial_controller($_GET);
    }else if($table === "expensesAndIncome" ){
        $response->getDataFilterExpensesAndIncome($_GET); 
    }else{
        $response->getData($table,$select); 
    }
  
}else{
    badResponse();
}




?>