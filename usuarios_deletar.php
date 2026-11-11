<?php
require 'includes/protege.php';
require 'classes/Usuario.class.php';

$u = new Usuario();
$msg = '';
$tipo = 'success';

if(isset($_POST['id'])){
    $id = (int)$_POST['id'];

    if($id == $_SESSION['id_usuario']){
        $msg = 'Você não pode apagar o usuário que está logado.';
        $tipo = 'danger';
    }elseif($u->apagar($id)){
        $msg = 'Usuário apagado com sucesso!';
    }else{
        $msg = 'Erro ao apagar o usuário.';
        $tipo = 'danger';
    }
}

$usuarios = $u->listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deletar Usuário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Deletar Usuário</h2>

        <?php if($msg != ''): ?>
            <div class="alert alert-<?php echo $tipo; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

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
                        <td>
                            <form method="post" onsubmit="return confirm('Apagar este usuário?');">
                                <input type="hidden" name="id" value="<?php echo $item['id_usuario']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>