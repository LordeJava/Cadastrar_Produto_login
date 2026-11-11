<?php
require 'includes/protege.php';
require 'classes/Usuario.class.php';

$msg = '';
$tipo = 'success';

if(isset($_POST['nome'])){
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $u = new Usuario();

    if($nome == '' || $email == '' || $senha == ''){
        $msg = 'Preencha todos os campos.';
        $tipo = 'danger';
    }elseif($u->emailExiste($email)){
        $msg = 'Este e-mail já está cadastrado.';
        $tipo = 'danger';
    }elseif($u->inserir($nome, $email, $senha)){
        $msg = 'Usuário cadastrado com sucesso!';
    }else{
        $msg = 'Erro ao cadastrar o usuário.';
        $tipo = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inserir Usuário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4" style="max-width: 500px;">
        <h2>Inserir Usuário</h2>

        <?php if($msg != ''): ?>
            <div class="alert alert-<?php echo $tipo; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" name="senha" id="senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-dark">Cadastrar</button>
        </form>
    </div>
</body>
</html>