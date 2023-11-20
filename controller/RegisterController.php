<?php

class RegisterController{
    private $viewRegister = "view/registerView.php";
    private $successPath = "success.php";
    private $modelRegion = "model/DAOregione.php";
    private $modelProvince = "model/DAOprovince.php";
    private $modelUser = "model/DAOuser.php";


    public function loadPage($popup){
        $this->requireDaoRegion();
        $this->requireDaoProvincie();
        $region = getRegioni();
        $provincie = getProvince();

        include($this->viewRegister);
    }

    private function requireDaoUser(){
        require_once $this->modelUser;
    }

    private function requireDaoRegion(){
        require_once $this->modelRegion;
    }

    private function requireDaoProvincie(){
        require_once $this->modelProvince;
    }

    private function JsonEncoder($arr){
        return "registerController".json_encode($arr)."registerController";
    }

    public function check(){
        if (isset($_POST['submit'])) {
            session_start();
            $this->requireDaoUser();
            $this->requireDaoProvincie();

            $user = new User($_POST['user-username'], $_POST['user-email'], hash('sha256', $_POST['user-password']), $_POST['user-name'], $_POST['user-surname'], 1, $_POST['user-address'], $_POST['user-picture'], $_POST['user-birthdate'], "", "", "");

            if (count(getUsers(array('email' => $user->getEmail()))) > 0) {
                $popup = 'Mail already used';
                //inclui a view
                $this->loadPage($popup);
            } else {
                if (count(getUsers(array('username' => $user->getUsername()))) > 0) {
                    $popup = 'Username already used';
                    //inclui a view
                    $this->loadPage($popup);
                } else {
                    $result = insertUser($user);

                    if ($result === false) {
                        $popup = ':( Something bad happend';
                        $this->loadPage($popup);
                    } else {
                        $_SESSION['username'] = $user->getUsername();
                        $_SESSION['password'] = $user->getPassword();

                        header("Location: " . $this->successPath);
                        die();
                    }
                }
            }
        } else {
            $this->loadPage("error incorrect program logic");
        }
    }

    public function getRequiredRegions(){
        $this->requireDaoRegion();
        $this->requireDaoProvincie();
        $codiceRegione = getRegioni(array("nome" => $_POST["regione"]))[0]->codice;
        $provincie =  getProvince(array("Regione" => $codiceRegione));
        echo $this->JsonEncoder($provincie);
    }
}

?>
