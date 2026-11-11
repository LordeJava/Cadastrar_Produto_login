<?php require 'includes/protege.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página Principal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>!</h1>
        <p class="lead">Use o menu acima para gerenciar produtos e usuários.</p>
    </div>
</body>
</html>