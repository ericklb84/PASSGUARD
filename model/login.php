<?php 
class ModelLogin{
    public function recebeDados($dados){
        return $this->verifica($dados);
    }   
    private function geraConexaoPDO(){
        return new PDO("mysql:host=localhost;dbname=login",'php','123mudar');
    }
    private function geraQuery($dados){
        define('SENHA_CRIPTOGRAFIA','123mudar');
        $usuario = $dados['usuario'];
        $senha = $dados['senha'];
        $db = $this->geraConexaoPDO();
        $sql = $db->prepare('SELECT usuario, CAST(AES_DECRYPT(senha,"'.SENHA_CRIPTOGRAFIA.'") AS CHAR(50)) as senha from usuario where usuario = ? AND senha = aes_encrypt(?,"'.SENHA_CRIPTOGRAFIA.'")');
        $sql->bindParam(1,$usuario);
        $sql->bindParam(2,$senha);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }
    private function verifica($dados){
        $usuario = $dados['usuario'];
        $senha = $dados['senha'];
        $row = $this->geraQuery($dados);
        if($row['usuario'] == $usuario && $row['senha'] == $senha){
            session_start();
            $_SESSION['idSession'] = $_COOKIE['PHPSESSID'];
            return true;
        }else{
            return false;
        }
    }
}