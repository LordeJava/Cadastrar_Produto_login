<?php

class Produto{
    private $nome;
    private $descricao;
    private $valor;
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

    public function enviarProduto($nome, $descricao, $valor, $fotos = array()){

        $this->conecta();

        //inserir Produto na tabela produtos
        //===================================
        $sql = "INSERT INTO produtos SET descricao = :d, nome_produto = :n, valor = :v";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":d", $descricao);
        $sql->bindValue(":n", $nome);
        $sql->bindValue(":v", $valor);

        $isOk = $sql->execute();

        if($isOk == true){
            $id_produto = $this->pdo->lastInsertId();
        }

        //inserir Imagem na tabela imagens
        //===================================
        if(count($fotos) > 0){
            for($i = 0; $i < count($fotos); $i++){
                $nome_foto = $fotos[$i];

                $sql = "INSERT INTO imagens (nome_imagem, fk_id_produto) values (:n, :fk)";
                $sql = $this->pdo->prepare($sql);
                $sql->bindValue(":n", $nome_foto);
                $sql->bindValue(":fk", $id_produto);

                $isOk = $sql->execute();
            }
        }

        return $isOk;
    }

    public function listarProdutos(){

        $this->conecta();

        //busca todos os produtos junto com o nome das imagens relacionadas
        $sql = "SELECT p.id_produto, p.nome_produto, p.descricao, p.valor, i.nome_imagem
                FROM produtos p
                LEFT JOIN imagens i ON i.fk_id_produto = p.id_produto
                ORDER BY p.id_produto DESC";
        $sql = $this->pdo->prepare($sql);
        $sql->execute();

        $linhas = $sql->fetchAll(PDO::FETCH_ASSOC);

        //agrupa as imagens dentro do produto correspondente (um produto pode ter varias fotos)
        $produtos = array();
        foreach($linhas as $linha){
            $id = $linha['id_produto'];

            if(!isset($produtos[$id])){
                $produtos[$id] = array(
                    'id_produto'   => $linha['id_produto'],
                    'nome_produto' => $linha['nome_produto'],
                    'descricao'    => $linha['descricao'],
                    'valor'        => $linha['valor'],
                    'imagens'      => array()
                );
            }

            if(!empty($linha['nome_imagem'])){
                $produtos[$id]['imagens'][] = $linha['nome_imagem'];
            }
        }

        return $produtos;
    }

    //APAGAR produto: primeiro as imagens (banco e arquivos), depois o produto
    public function apagarProduto($id){

        $this->conecta();

        //pega o nome dos arquivos de imagem do produto
        $sql = "SELECT nome_imagem FROM imagens WHERE fk_id_produto = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $id);
        $sql->execute();
        $arquivos = $sql->fetchAll(PDO::FETCH_COLUMN);

        try {
            $this->pdo->beginTransaction();

            //apaga as imagens do banco (precisa ser antes por causa da chave estrangeira)
            $sql = "DELETE FROM imagens WHERE fk_id_produto = :id";
            $sql = $this->pdo->prepare($sql);
            $sql->bindValue(":id", $id);
            $sql->execute();

            //apaga o produto
            $sql = "DELETE FROM produtos WHERE id_produto = :id";
            $sql = $this->pdo->prepare($sql);
            $sql->bindValue(":id", $id);
            $sql->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }

        //apaga os arquivos da pasta imagens
        foreach($arquivos as $arquivo){
            $caminho = __DIR__ . '/../imagens/' . basename($arquivo);
            if(is_file($caminho)){
                unlink($caminho);
            }
        }

        return true;
    }
}