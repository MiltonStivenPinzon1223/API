<?php

require_once "controller/userController.php";
require_once "controller/routesController.php";
require_once "controller/LoginController.php";
require_once "model/userModel.php";

header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Headers: Authorization');
//$rutasArray = $_SERVER['REQUEST_URI'];

$rutasArray = explode("/", $_SERVER['REQUEST_URI']);
$endPoint = (array_filter($rutasArray)[2]); // colocar un if

/*if($endPoint!='login'){
    if(isset($_SERVER['PHP_AUTH_USER']) && (isset($_SERVER['PHP_AUTH_PW']))){
        $ok = false;
        $identifier = $_SERVER['PHP_AUTH_USER'];
        $key = $_SERVER['PHP_AUTH_PW'];
        $user = UserModel::getUserAuth();
        foreach ($users as $u){
            if($identifier. ":" .$key == $u["us_identifier"]. ":" .$u["us_key"]){
                $ok = true;
            }
        }
        if($ok){
            $routes = new RoutesController();
            $routes -> index();
        }else{
            $result["mensaje"] = "NO TIENE ACCESO";
            echo json_encode($result, true);
            return;
        }
    }else{
        $result["mensaje"] = "ERROR DE CREDENCIALES";
        echo json_encode($result, true);
        return;
    }
}else{*/
    $routes = new RoutesController();
    $routes->index();    
//}
?>