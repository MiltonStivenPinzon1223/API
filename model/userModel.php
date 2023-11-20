<?php

require_once "ConDB.php";
class UserModel{   
          
static public function createUser($data){
        $cantMail = self::getMail($data["use_mail"]);
        if($cantMail==0){
            $date = date("Y-m-d");
            $status = "1";
            $query = "INSERT INTO users (use_mail,use_pss,use_dateCreate, us_identifier,us_key,us_status) VALUES ('".$data['use_mail']."', '".$data['use_pss']."', '".$date."', '".$data['us_identifier']."', '".$data['us_key']."', '".$status."');";
            $statement = Connection::connection()->prepare($query);
            $message = $statement-> execute() ? array("ok") : Connection::connection()->errorInfo();
            $statement->closeCursor();
            $statement = null;
            $query="";
        }else{$message = array("el usuario ya existe");}
        return $message;
    }

    static private function getMail($mail){
        $query = "SELECT use_mail FROM users WHERE use_mail = '$mail'";
        $statement = Connection::connection()->prepare($query);
        $statement->execute();
        $result = $statement->rowCount();
        return $result;
    }

    static private function getStatus($id){
        $query = "SELECT us_status FROM users WHERE use_id = '$id'";
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

    static public function update($id,$data){
        $pass = md5($data['use_pss']);
        $query = "UPDATE users SET use_mail='".$data['use_mail']."',use_pss='".$pass."' WHERE use_id = ".$id.";";
        $statement = Connection::connection()->prepare($query);
        $statement->execute();
        $msg = array(
            "msg"=>"Usuario actualizado"
        );
        return $msg;
    }

    static public function updateStatus($id){
        $status = self::getStatus($id);
        $newStatus = ($status == 0) ? 1 : 0;
        $query = "UPDATE users SET us_status='".$newStatus."' WHERE use_id = ".$id.";";
        $statement = Connection::connection()->prepare($query);
        $statement->execute();
        $msg = array(
            "msg"=>"Usuario Eliminado"
        );
        return $msg;
    }
}
?>
