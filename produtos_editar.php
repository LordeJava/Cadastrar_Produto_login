<?php
require 'includes/protege.php';
require 'classes/Produto.class.php';

$p = new Produto();
$msg = '';
$tipo = 'success';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id        = (int)$_POST['id'];
    $nome      = trim($_POST['nome']);
    $descricao = trim($_POST['desc']);
    $valor     = trim($_POST['valor']);
    $remover   = isset($_POST['remover']) ? array_map('intval', $_POST['remover']) : array();

    if($nome == '' || $descricao == '' || $valor == '' || !is_numeric($valor) || $valor < 0){
        $msg = 'Preencha nome, descrição e um valor válido.';
        $tipo = 'danger';
    }else{
        //trata as fotos novas (apenas JPG e PNG)
        $fotosNovas = array();
        $fotoInvalida = false;

        if(isset($_FILES['foto'])){
            for($i = 0; $i < count($_FILES['foto']['name']); $i++){

                //ignora se nenhum arquivo foi escolhido
                if($_FILES['foto']['error'][$i] == UPLOAD_ERR_NO_FILE){
                    continue;
                }

                if($_FILES['foto']['type'][$i] == 'image/png'){
                    $ext = '.png';
                }elseif($_FILES['foto']['type'][$i] == 'image/jpeg'){
                    $ext = '.jpg';
                }else{
                    $fotoInvalida = true;
                    continue;
                }

                $nome_arquivo = md5($_FILES['foto']['name'][$i]).rand(1,999).$ext;
                move_uploaded_file($_FILES['foto']['tmp_name'][$i], 'imagens/'.$nome_arquivo);
                $fotosNovas[] = $nome_arquivo;
            }
        }

        if($p->alterarProduto($id, $nome, $descricao, $valor, $fotosNovas, $remover)){
            $msg = 'Produto atualizado com sucesso!';
            if($fotoInvalida){
                $msg .= ' (Arquivos que não eram JPG ou PNG foram ignorados.)';
            }
        }else{
            $msg = 'Erro ao atualizar o produto.';
            $tipo = 'danger';
        }
    }
}

$produto = ($id > 0) ? $p->buscarProduto($id) : false;

//produto nao existe: volta para a lista
if(!$produto){
    header('Location: produtos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4 mb-5" style="max-width: 600px;">
        <h2>Editar Produto</h2>

        <?php if($msg != ''): ?>
            <div class="alert alert-<?php echo $tipo; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $produto['id_produto']; ?>">

            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($produto['nome_produto']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="desc" class="form-label">Descrição</label>
                <textarea name="desc" id="desc" class="form-control" rows="4" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="valor" class="form-label">Valor</label>
                <input type="number" name="valor" id="valor" class="form-control" step="0.01" min="0" value="<?php echo htmlspecialchars($produto['valor']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Fotos atuais</label>
                <?php if(empty($produto['imagens'])): ?>
                    <p class="text-muted">Este produto não tem fotos.</p>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-3">
                        <?php foreach($produto['imagens'] as $img): ?>
                            <div class="text-center">
                                <img src="imagens/<?php echo htmlspecialchars($img['nome_imagem']); ?>" class="img-thumbnail" style="width:120px; height:120px; object-fit:cover;" alt="foto">
                                <div class="form-check d-flex justify-content-center gap-1 mt-1">
                                    <input class="form-check-input" type="checkbox" name="remover[]" value="<?php echo $img['id_imagem']; ?>" id="rem<?php echo $img['id_imagem']; ?>">
                                    <label class="form-check-label" for="rem<?php echo $img['id_imagem']; ?>">Remover</label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Adicionar novas fotos (JPG ou PNG)</label>
                <input type="file" name="foto[]" id="foto" class="form-control" multiple accept=".jpg,.jpeg,.png">
            </div>

            <button type="submit" class="btn btn-dark">Salvar alterações</button>
            <a href="produtos.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>