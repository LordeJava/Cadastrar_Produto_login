CREATE DATABASE IF NOT EXISTS loja_etim CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE loja_etim;

CREATE TABLE produtos(
    id_produto int AUTO_INCREMENT PRIMARY KEY,
    nome_produto varchar(100),
    descricao text,
    valor decimal(10,2)
);

CREATE TABLE imagens(
    id_imagem int AUTO_INCREMENT PRIMARY KEY,
    nome_imagem varchar(100),
    fk_id_produto int,
    FOREIGN KEY(fk_id_produto) REFERENCES produtos(id_produto)
);

CREATE TABLE usuarios(
    id_usuario int AUTO_INCREMENT PRIMARY KEY,
    nome varchar(100) NOT NULL,
    email varchar(100) NOT NULL UNIQUE,
    senha varchar(255) NOT NULL
);

-- usuario inicial: e-mail admin@admin.com / senha: password
-- (troque a senha depois em Usuarios > Atualizar)
INSERT INTO usuarios (nome, email, senha) VALUES
('Administrador', 'admin@admin.com', '123456');