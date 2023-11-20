<?php

class UserController{
    private $_method; //get, post, put.
    private $_complement; //get user 1 o 2.
    private $_data; // datos a insertar o actualizar

    function __construct($method,$complement,$data){
        //var_dump($data);
        $this->_method = $method;
        $this->_complement = $complement;
        $this->_data = $data !=0 ? $data : "";
       
    }

    public function index(){
        switch($this->_method){
            case "GET":
                switch($this->_complement){
                    case 0:
                        $user = UserModel::getUsers(0);
                        $json = $user;
                        echo json_encode($json);
                        return;
                    default:
                        $user = UserModel::getUsers($this->_complement);
                        if ($user==null) 
                            $json = ["msg"=>"No existe el usuario"];
                        else
                            $json = $user;
                        echo json_encode($json);
                        return;
                }
            case "POST":
                $createUser = UserModel::createUser($this->generateSalting());
                $json = array(
                    "response: "=>$createUser
                );
                echo json_encode($json,true);
                return;
            case "PUT":
                $createUser = UserModel::update($this->_complement,$this->_data);
                $json = array(
                    "response: "=>$createUser
                );
                echo json_encode($json,true);
                return;
            case "DELETE":
                $createUser = UserModel::updateStatus($this->_complement);
                $json = array(
                    "response: "=>$createUser
                );
                echo json_encode($json,true);
                return;
            default:
                $json = array(
                    "ruta: "=>"not found"
                );
                echo json_encode($json,true);
                return;
        }
    }

    private function generateSalting(){
        $trimmed_data="";
        //var_dump($this->_data);
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/';
        if (preg_match($pattern, $this->_data['use_pss'])) {
            if(($this->_data !="") || (!empty($this->_data))){
                $trimmed_data = array_map('trim', $this->_data);
                $trimmed_data['use_pss'] = md5($trimmed_data['use_pss']);
                //salting
                $identifier = str_replace("$", "y78", crypt($trimmed_data['use_mail'], 'ser3478'));
                $key = str_replace("$", "ERT", crypt($trimmed_data['use_pss'], '$uniempresarial2024'));
                $trimmed_data['us_identifier'] = $identifier;
                $trimmed_data['us_key'] = $key;
                return $trimmed_data;
            }
        } else {
            $message = "la clave no cuenta con los parametros requeridos";
            die($message);
        }
    }
}

?>