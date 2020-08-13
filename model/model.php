<?php 
class Model{
    private function openDB(){
        $db = new mysqli('localhost','php','123mudar',"pass");
        return $db;
    }
    public function read(){
        $db = $this->openDB();
        $res = $db->query('SELECT id,nome,telefone FROM usuario');
        while($row = $res->fetch_assoc()) {
            $resp[] = $row; 
        }
        return $resp;
    }
    public function search($chave){
        $db = $this->openDB();
        $res = $db->query('SELECT id,nome,telefone FROM usuario WHERE nome LIKE "%'. $chave .'%"');
        while($row = $res->fetch_assoc()) {
            $resp[] = $row; 
        }
        return $resp;
    }
    public function chaves($id){
        $db = $this->openDB();
        $res = $db->query('SELECT id,nome,telefone FROM usuario');
        while($row = $res->fetch_assoc()) {
            $resp['usr'][] = $row; 
        }
        $res = $db->query('SELECT id,
        CAST(AES_DECRYPT(funcao,"4724972cc7ee733097e2") AS CHAR (50)) as funcao,
        CAST(AES_DECRYPT(`url`,"4724972cc7ee733097e2") AS CHAR (50)) as `url`,
        CAST(AES_DECRYPT(`usuario`,"4724972cc7ee733097e2") AS CHAR (50)) as `usuario`,
        CAST(AES_DECRYPT(`senha`,"4724972cc7ee733097e2") AS CHAR (50)) as `senha`
        FROM dados WHERE cliente = "'. $id .'"');
        while($row = $res->fetch_assoc()) {
            $i++;
            $resp['key'][$i]['id']      = $row['id'];
            $resp['key'][$i]['funcao']  = base64_decode($row['funcao']); 
            $resp['key'][$i]['url']     = base64_decode($row['url']); 
            $resp['key'][$i]['usuario'] = base64_decode($row['usuario']); 
            $resp['key'][$i]['senha']   = base64_decode($row['senha']); 
        }
        $res = $db->query('SELECT anotacao FROM anotacoes WHERE cliente = "'. $id .'" ORDER BY data_criacao');
        $resp['anotacoes'][] = $res->fetch_assoc(); 
        return $resp;
    }
    public function drops($id){
        $db = $this->openDB();
        $db->query('DELETE FROM usuario WHERE id ='.$id);
        $db->query('DELETE FROM anotacoes WHERE cliente ='.$id);
        $db->query('DELETE FROM dados WHERE cliente ='.$id);
    }
    public function editReg($dados){
        $db = $this->openDB();
        $linha = 'UPDATE usuario SET nome="'. $dados['nome'] .'",`telefone`="'. $dados['telefone'] .'" WHERE id = '. $dados['id'];
        $db->query($linha);
    }
    
    public function addData($dados){
        $db = $this->openDB();
        $linha = 'INSERT INTO dados(`funcao`,`url`,`usuario`,`senha`,`cliente`) VALUES(AES_ENCRYPT("'. base64_encode($dados['tipo']) .'","4724972cc7ee733097e2"),AES_ENCRYPT("'. base64_encode($dados['url']) .'","4724972cc7ee733097e2"),AES_ENCRYPT("'. base64_encode($dados['usuario']) .'","4724972cc7ee733097e2"),AES_ENCRYPT("'. base64_encode($dados['senha']) .'","4724972cc7ee733097e2"),'.$dados['id'].')';
        $db->query($linha);
    }
    public function edtDta($dados){
        $db = $this->openDB();
        $linha = 'UPDATE dados SET `funcao`="'. $dados['tipo'] .'",`url`="'. $dados['url'] .'",`usuario`="'. $dados['usuario'] .'",`senha`="'. $dados['senha'] .'" WHERE id='.$dados['id'];
        $db->query($linha);
    }
    public function dropDta($id){
        $db = $this->openDB();
        $linha = 'DELETE FROM dados WHERE id='.$id;
        $db->query($linha);
    }
    public function addUsr($dados){
        $db = $this->openDB();
        $db->query('INSERT INTO usuario(`nome`,`telefone`) VALUES ("'. $dados['nome'] .'","'. $dados['telefone'] .'")');
        $res = $db->query('SELECT id FROM usuario WHERE `nome` = "'. $dados['nome'] .'" AND `telefone`="'. $dados['telefone'] .'"');
        $id = $res->fetch_assoc()['id'];
        $db->query('INSERT INTO anotacoes (`anotacao`,`data_criacao`,`cliente`) VALUES ("'. $dados['descricao'] .'",NOW(),"'. $id .'")');
        return $id;
    }
}


?>