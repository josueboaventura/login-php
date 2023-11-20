<?php

class LoginController {
    private $viewLogin = "view/loginView.php";
    private $successPath = "success.php";
    private $modelProvincie = "model/DAOprovince.php";
    private $modelUser = "model/DAOuser.php";

    /*private function SetupPopup($message){
        $popup = $message;
    }*/

    public function loadPage($popup){
        include ($this->viewLogin);
    }

    private function requireDaoUser(){
        require_once $this->modelUser;
    }

    private function requireDaoProvincie(){
        require_once $this->modelProvincie;
    }

    public function check(){
        if(isset($_POST['submit'])){
            session_start();
            $this->requireDaoUser();
        
            $user_username =  $_POST['user-username'];
            $user_password =  hash('sha256',$_POST['user-password']);
            $filterCredential = array('username'=>$user_username,'password'=>$user_password);
        
            if(count(getUsers($filterCredential)) == 1){
        
                $_SESSION['username'] = $user_username;
                $_SESSION['password'] = $user_password;
                
                //var_dump($_REQUEST);
                
                header("Location: ".$this->successPath); die();
            } else {
                //prepare the popup in case of error login invalid
                $popup = 'Invalid username and password';
                $this->loadPage($popup);
            }
        } else {
            $this->loadPage("error incorrect program logic");
        }
    }
    
}

?>
