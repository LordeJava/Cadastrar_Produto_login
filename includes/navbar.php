<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="principal.php">Loja ETIM</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="produtos.php">Produtos cadastrados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Inserir produto</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Usuários</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="usuarios_inserir.php">Inserir</a></li>
                        <li><a class="dropdown-item" href="usuarios_atualizar.php">Atualizar</a></li>
                        <li><a class="dropdown-item" href="usuarios_listar.php">Listar</a></li>
                        <li><a class="dropdown-item" href="usuarios_deletar.php">Deletar</a></li>
                    </ul>
                </li>
            </ul>

            <span class="navbar-text me-3">Olá, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?></span>
            <a class="btn btn-outline-light btn-sm" href="logout.php">Sair</a>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>