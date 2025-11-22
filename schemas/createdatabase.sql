CREATE DATABASE IF NOT EXISTS login;

CREATE TABLE IF NOT EXISTS login.usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    sobrenome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    sexo ENUM('M', 'F', 'O') NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nivel_acesso ENUM('admin', 'user') DEFAULT 'user',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

