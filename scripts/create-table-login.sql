-- =====================================================
-- Script de Criação do Banco de Dados e Tabelas
-- Sistema de Login com PHP e MySQL
-- =====================================================

-- Cria o banco de dados se não existir
CREATE DATABASE IF NOT EXISTS login CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE login;

-- Remove a tabela se existir (apenas para desenvolvimento)
-- DROP TABLE IF EXISTS usuarios;

-- Cria a tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL COMMENT 'Nome do usuário',
    sobrenome VARCHAR(100) NOT NULL COMMENT 'Sobrenome do usuário',
    email VARCHAR(150) NOT NULL UNIQUE COMMENT 'Email único do usuário',
    sexo ENUM('M', 'F', 'O') NOT NULL DEFAULT 'O' COMMENT 'Sexo do usuário',
    senha VARCHAR(255) NOT NULL COMMENT 'Hash da senha (password_hash)',
    nivel_acesso ENUM('admin', 'user') DEFAULT 'user' COMMENT 'Nível de acesso do usuário',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de cadastro',
    ultima_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Última atualização'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabela de usuários do sistema';

-- Cria índices para otimização de consultas
CREATE INDEX idx_email ON usuarios(email) COMMENT 'Índice para busca por email';
CREATE INDEX idx_nivel_acesso ON usuarios(nivel_acesso) COMMENT 'Índice para filtragem por nível de acesso';
CREATE INDEX idx_data_cadastro ON usuarios(data_cadastro) COMMENT 'Índice para ordenação por data';

-- Insere usuários de exemplo (senhas criptografadas com password_hash)
-- Senha do admin: admin123
-- Senha do user: user123
INSERT INTO usuarios (nome, sobrenome, email, sexo, senha, nivel_acesso) VALUES
('Administrador', 'Sistema', 'admin@admin.com', 'O', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Usuario', 'Teste', 'user@user.com', 'M', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user')
ON DUPLICATE KEY UPDATE nome=VALUES(nome);

COMMIT;



