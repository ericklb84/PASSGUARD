<?php 
define('HTTP_SERVER','http://localhost/~erick/pass/controller/controller.php');
class ControllerLogin{
    public function index($dados){
        if(isset($_SESSION)){
            session_destroy();
        }
        if(isset($dados['usuario'])){
            $this->verifica($dados);
        }else{
            $this->mostraLogin();
        }
    }
    private function mostraLogin(){
        require(__DIR__.'/../view/login.php');
    }
    private function verifica($dados){
        require(__DIR__.'/../model/login.php');
        $loginModel = new ModelLogin();
        if($loginModel->recebeDados($dados)){
            header('Location: '.HTTP_SERVER);
        }else{
            $this->mostraLogin();
        }
    }
}

$new = new ControllerLogin();

$new->index($_POST);

?>