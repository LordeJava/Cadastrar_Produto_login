<?php
require 'includes/protege.php';
require 'classes/Usuario.class.php';

$u = new Usuario();
$usuarios = $u->listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listar Usuários</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Usuários Cadastrados</h2>

        <?php if(empty($usuarios)): ?>
            <p>Nenhum usuário cadastrado.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr><th>ID</th><th>Nome</th><th>E-mail</th></tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id_usuario']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>