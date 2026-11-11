<?php
require 'includes/protege.php';
require 'classes/Usuario.class.php';

$u = new Usuario();
$msg = '';
$tipo = 'success';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id    = (int)$_POST['id'];
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if($nome == '' || $email == ''){
        $msg = 'Preencha nome e e-mail.';
        $tipo = 'danger';
    }elseif($u->emailExiste($email, $id)){
        $msg = 'Este e-mail já pertence a outro usuário.';
        $tipo = 'danger';
    }elseif($u->alterar($id, $nome, $email, $senha)){
        $msg = 'Usuário atualizado com sucesso!';
        if($id == $_SESSION['id_usuario']){
            $_SESSION['nome_usuario'] = $nome;
        }
    }else{
        $msg = 'Erro ao atualizar o usuário.';
        $tipo = 'danger';
    }
}

$usuario = ($id > 0) ? $u->buscar($id) : false;
$usuarios = $u->listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atualizar Usuário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Atualizar Usuário</h2>

        <?php if($msg != ''): ?>
            <div class="alert alert-<?php echo $tipo; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <?php if($usuario): ?>
            <form method="post" style="max-width: 500px;">
                <input type="hidden" name="id" value="<?php echo $usuario['id_usuario']; ?>">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Nova senha (deixe em branco para manter a atual)</label>
                    <input type="password" name="senha" id="senha" class="form-control">
                </div>
                <button type="submit" class="btn btn-dark">Salvar</button>
                <a href="usuarios_atualizar.php" class="btn btn-secondary">Voltar</a>
            </form>
        <?php else: ?>
            <p>Escolha o usuário que deseja editar:</p>
            <table class="table table-striped">
                <thead>
                    <tr><th>ID</th><th>Nome</th><th>E-mail</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $item): ?>
                        <tr>
                            <td><?php echo $item['id_usuario']; ?></td>
                            <td><?php echo htmlspecialchars($item['nome']); ?></td>
                            <td><?php echo htmlspecialchars($item['email']); ?></td>
                            <td><a class="btn btn-sm btn-primary" href="usuarios_atualizar.php?id=<?php echo $item['id_usuario']; ?>">Editar</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>