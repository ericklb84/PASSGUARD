<?php 
include(__DIR__."/../model/model.php");
define('HTTP_SERVER','http://localhost/~erick/pass/controller/controller.php');
class Controller{
    public function index($dados,$queries){
        session_start();
        if($_SESSION['idSession'] == $_COOKIE['PHPSESSID']){
        if(isset($dados['funcao'])){
            $this->seta($dados);
        }else if(isset($queries['id']) && !isset($queries['op']) && !isset($dados['op'])){
            $this->showData($queries['id']);
        }else if(isset($queries['op']) && isset($queries['id'])){
            if($queries['op'] == "exc"){
                $this->dropReg($queries['id']);
            }
            if($queries['op'] == "exc_dta"){
                $this->dropDta($queries['id_dta'],$queries['id']);
            }
        }else if(isset($dados['op'])){
            if($dados['op'] == "ED"){
                $this->editarReg($dados);
            }else if($dados['op'] == "ADD-DTA"){
                $this->addData($dados);
            }else if($dados['op'] == "ED-DTA"){
                $this->edtDta($dados);
            }else if($dados['op']=="add-usr"){
                $this->addUsr($dados);
            }
        }else{
            $this->show();
        }
    }else{
        header('Location: http://localhost/~erick/pass/controller/login.php');
    }
    }
    private function seta($dados){
        if($dados['funcao'] == "01"){
            $this->search($dados['chave']);
        }
    }
    private function show(){
        $model = new Model();
        $usuarios = $model->read();
        include(__DIR__."/../view/view.php");
    }
    private function search($chave){
        $model = new Model();
        $usuarios = $model->search($chave);
        include(__DIR__."/../view/view.php");
    }
    private function showData($chave){
        $model = new Model();
        $res = $model->chaves($chave);
        $chaves = $res['key'];
        $usuarios = $res['usr'];
        $anotacao = $res['anotacoes'];
        include(__DIR__."/../view/view.php");
    }
    private function dropReg($id){
        $model = new Model();
        $model->drops($id);
        header('Location: '.HTTP_SERVER);
    }
    private function editarReg($dados){
        $model = new Model();
        $model->editReg($dados);
        header('Location: '.HTTP_SERVER);
    }
    private function addData($dados){
        $model = new Model();
        $model->addData($dados);
        header('Location: '.HTTP_SERVER."?id=".$dados['id']);
    }
    private function edtDta($dados){
        $model = new Model();
        $model->edtDta($dados);
        header('Location: '.HTTP_SERVER."?id=".$_GET['id']);
    }
    private function dropDta($id_prod,$id){
        $model = new Model();
        $model->dropDta($id_prod);
        header('Location: '.HTTP_SERVER."?id=".$id);
    }
    private function addUsr($dados){
        $model = new Model();
        $id = $model->addUsr($dados);
        header('Location: '.HTTP_SERVER."?id=".$id);
    }
}

$ctrl = new Controller();

$ctrl->index($_POST,$_GET);
