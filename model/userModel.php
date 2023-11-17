<?php

require_once "ConDB.php";
class UserModel{   
          
static public function createUser($data){
        $cantMail = self::getMail($data["use_mail"]);
        if($cantMail==0){
            $date = date("Y-m-d");
            $query = "INSERT INTO users (use_id,use_mail,use_pss,use_dateCreate, us_identifier,us_key,us_status)
            VALUES (NULL,:use_mail,:use_pss,:use_dateCreate,:us_identifier,:us_key,:us_status);";
            $status = "1";
            $statement = Connection::connection()->prepare($query);
            $statement-> bindParam(":use_mail", $data["use_mail"],PDO::PARAM_STR);
            $statement-> bindParam(":use_pss", $data["use_pss"],PDO::PARAM_STR);
            $statement-> bindParam(":use_dateCreate", $date,PDO::PARAM_STR);
            $statement-> bindParam(":us_identifier", $data["us_identifier"],PDO::PARAM_STR);
            $statement-> bindParam(":us_key", $data["us_key"],PDO::PARAM_STR);
            $statement-> bindParam(":us_status", $status,PDO::PARAM_STR);
            $message = $statement-> execute() ? "ok" : Connection::connection()->errorInfo();
            $statement->closeCursor();
            $statement = null;
            $query="";
    }else{
        $message = "el usuario ya existe";
    }
    return $message;
}
    static private function getMail($mail){
        $query = "SELECT use_mail FROM users WHERE use_mail = '$mail'";
        $statement = Connection::connection()->prepare($query);
        $statement->execute();
        $result = $statement->rowCount();        
        return $result;
    }

    static public function getUsers($parametro){
        $param = is_numeric($parametro) ? $parametro : 0;
        $query = "SELECT use_id, use_mail, use_dateCreate FROM users";
        $query .= ($param > 0) ? " WHERE users.use_id = '$param' AND " : "";
        $query .= ($param > 0) ? " us_status = '1';" : " WHERE us_status = '1';";
        //echo query 
        $statement = Connection::connection()->prepare($query);
        $statement -> execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    static public function login($data){
        $user = $data['use_mail'];
        $pass = md5($data['use_pss']);

        if (!empty($user) && !empty($pass)){
            $query="SELECT us_identifier, us_key, use_id FROM users WHERE use_mail = '$user' and use_pss='$pass' and us_status='1'";
            $statement = Connection::connection()->prepare($query);
            $statement-> execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }else{
            return "NO TIENE CREDENCIALES";
        }
    }
    static public function getUserAuth(){
        $query = "";
        $query = "SELECT us_identifier, us_key FROM users WHERE us_status = '1';";
        $statement = Connection::connection()->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
?>
