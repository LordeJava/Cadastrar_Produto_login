<?php

class Usuario{
    private $pdo;

    public function conecta(){
        $dns  = "mysql:dbname=loja_etim;host=localhost";
        $user = "root";
        $pass = "";
        try {
            $this->pdo = new PDO($dns, $user, $pass);
            return true;
        } catch (Exception $e) {
            echo "Erro de conexao com o banco: " . $e->getMessage();
            return false;
        }
    }

    //verifica se o e-mail ja existe (ignora o proprio usuario ao alterar)
    public function emailExiste($email, $ignorarId = 0){
        $this->conecta();

        $sql = "SELECT id_usuario FROM usuarios WHERE email = :e AND id_usuario <> :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":e", $email);
        $sql->bindValue(":id", $ignorarId);
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    //INSERIR
    public function inserir($nome, $email, $senha){
        $this->conecta();

        $sql = "INSERT INTO usuarios SET nome = :n, email = :e, senha = :s";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":n", $nome);
        $sql->bindValue(":e", $email);
        $sql->bindValue(":s", password_hash($senha, PASSWORD_DEFAULT));

        return $sql->execute();
    }

    //ALTERAR (se a senha vier vazia, mantem a senha atual)
    public function alterar($id, $nome, $email, $senha = ''){
        $this->conecta();

        if($senha != ''){
            $sql = "UPDATE usuarios SET nome = :n, email = :e, senha = :s WHERE id_usuario = :id";
            $sql = $this->pdo->prepare($sql);
            $sql->bindValue(":s", password_hash($senha, PASSWORD_DEFAULT));
        }else{
            $sql = "UPDATE usuarios SET nome = :n, email = :e WHERE id_usuario = :id";
            $sql = $this->pdo->prepare($sql);
        }
        $sql->bindValue(":n", $nome);
        $sql->bindValue(":e", $email);
        $sql->bindValue(":id", $id);

        return $sql->execute();
    }

    //APAGAR
    public function apagar($id){
        $this->conecta();

        $sql = "DELETE FROM usuarios WHERE id_usuario = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);

        return $sql->execute();
    }

    //LISTAR todos
    public function listar(){
        $this->conecta();

        $sql = "SELECT id_usuario, nome, email FROM usuarios ORDER BY nome";
        $sql = $this->pdo->prepare($sql);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //BUSCAR um usuario pelo id (retorna array ou false)
    public function buscar($id){
        $this->conecta();

        $sql = "SELECT id_usuario, nome, email FROM usuarios WHERE id_usuario = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    //LOGIN (retorna os dados do usuario se acertou, ou false)
    public function login($email, $senha){
        $this->conecta();

        $sql = "SELECT * FROM usuarios WHERE email = :e";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":e", $email);
        $sql->execute();

        $dados = $sql->fetch(PDO::FETCH_ASSOC);

        if($dados && password_verify($senha, $dados['senha'])){
            return $dados;
        }
        return false;
    }
}