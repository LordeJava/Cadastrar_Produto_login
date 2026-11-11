<?php require 'includes/protege.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Cadastro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <section>
        <form method="post" enctype="multipart/form-data">
            <h1>CADASTRO DE PRODUTO</h1>
            <label for="nome">Nome do Produto</label>
            <input type="text" name="nome" id="nome" class="sombra">

            <label for="desc">Descrição</label>
            <textarea name="desc" id="desc" class="sombra"></textarea>

            <label for="val">Valor</label>
            <input type="number" name="valor" id="val" class="sombra" step="0.01" min="0">

            <input type="file" name="foto[]" multiple id="foto" class="sombra meuInput">
            <input type="submit" id="botao" value="Enviar">
        </form>
    </section>

<?php
//checa se o usuario clicou no botao (preencheu ao menos o nome)
if(isset($_POST['nome']) && !empty($_POST['nome'])){

    //coloca o dado preenchido em uma variavel e checa se nao tem injection
    $nome      = addslashes($_POST['nome']);
    $descricao = addslashes($_POST['desc']);
    $valor     = addslashes($_POST['valor']);

    //cria um array vazio para guardar os nomes das fotos caso tenha enviado
    $fotos = array();

    //checa se o usuario selecionou alguma imagem
    if(isset($_FILES['foto'])){
        $tipo = '';

        //cria um laco e repete enquanto houver fotos
        for($i = 0; $i < count($_FILES['foto']['name']); $i++){

            //checa a extensao para poder enviar apenas PNG e JPG
            if($_FILES['foto']['type'][$i] == "image/png"){
                $tipo = ".png";
            }elseif($_FILES['foto']['type'][$i] == 'image/jpeg'){
                $tipo = ".jpg";
            }else{
                $tipo = "outro";
            }

            if($tipo == 'outro'){
                ?>
                <script>
                    alert("Só é possível enviar arquivos JPG e PNG");
                </script>
                <?php
            }else{
                //gera um nome unico (evita sobrepor arquivo com mesmo nome)
                $nome_arquivo = md5($_FILES['foto']['name'][$i]).rand(1,999).$tipo;

                //move o arquivo da pasta temporaria para a pasta imagens, ja com o nome novo
                move_uploaded_file($_FILES['foto']['tmp_name'][$i], 'imagens/'.$nome_arquivo);

                //armazena o nome do arquivo no vetor fotos
                array_push($fotos, $nome_arquivo);
            }
        }
    }

    //verifica se todos os campos obrigatorios foram preenchidos
    if(!empty($nome) && !empty($descricao) && !empty($valor)){
        require 'classes/Produto.class.php';
        $p = new Produto();
        $isOk = $p->enviarProduto($nome, $descricao, $valor, $fotos);

        if($isOk){
            echo "<script>alert('Produto cadastrado com sucesso!');</script>";
        }else{
            echo "<script>alert('Erro ao cadastrar o produto.');</script>";
        }
    }else{
        ?>
        <script>
            alert("Preencha os campos obrigatorios!")
        </script>
        <?php
    }
}
?>
</body>
</html>