<?php
session_start();

//se ja esta logado, vai direto para a principal
if(isset($_SESSION['id_usuario'])){
    header('Location: principal.php');
    exit;
}

$erro = '';

if(isset($_POST['email'])){
    require 'classes/Usuario.class.php';
    $u = new Usuario();
    $dados = $u->login(trim($_POST['email']), $_POST['senha']);

    if($dados){
        session_regenerate_id(true);
        $_SESSION['id_usuario']   = $dados['id_usuario'];
        $_SESSION['nome_usuario'] = $dados['nome'];
        header('Location: principal.php');
        exit;
    }else{
        $erro = 'E-mail ou senha incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container" style="max-width: 400px; margin-top: 100px;">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Login</h3>

                <?php if($erro != ''): ?>
                    <div class="alert alert-danger"><?php echo $erro; ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" name="senha" id="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>