<?php
require 'includes/protege.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'classes/Produto.class.php';
$p = new Produto();

$msg = '';
$tipo = 'success';

//se clicou em Excluir, apaga o produto
if(isset($_POST['apagar_id'])){
    if($p->apagarProduto((int)$_POST['apagar_id'])){
        $msg = 'Produto excluído com sucesso!';
    }else{
        $msg = 'Erro ao excluir o produto.';
        $tipo = 'danger';
    }
}

$produtos = $p->listarProdutos();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produtos Cadastrados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="secao-produtos">
        <h1>Produtos Cadastrados</h1>

        <?php if($msg != ''): ?>
            <div class="alert alert-<?php echo $tipo; ?> text-center mx-4"><?php echo $msg; ?></div>
        <?php endif; ?>

        <?php if(empty($produtos)): ?>
            <p class="aviso-vazio">Nenhum produto cadastrado ainda.</p>
        <?php else: ?>
            <div class="lista-produtos">
                <?php foreach($produtos as $produto): ?>
                    <div class="produto-card sombra">

                        <?php if(!empty($produto['imagens'])): ?>
                            <div class="produto-fotos">
                                <?php foreach($produto['imagens'] as $img): ?>
                                    <img src="imagens/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($produto['nome_produto']); ?>">
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="produto-sem-foto">Sem foto</div>
                        <?php endif; ?>

                        <h2><?php echo htmlspecialchars($produto['nome_produto']); ?></h2>
                        <p class="produto-desc"><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
                        <p class="produto-preco">R$ <?php echo number_format($produto['valor'], 2, ',', '.'); ?></p>

                        <div class="d-grid gap-2 mt-2">
                            <a class="btn btn-primary btn-sm" href="produtos_editar.php?id=<?php echo $produto['id_produto']; ?>">Editar</a>

                            <form method="post" style="width:auto; margin:0; padding:0;" onsubmit="return confirm('Excluir este produto e as imagens dele?');">
                                <button type="submit" name="apagar_id" value="<?php echo $produto['id_produto']; ?>" class="btn btn-danger btn-sm" style="box-sizing:border-box; width:100%;">Excluir</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</body>
</html>