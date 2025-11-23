CREATE DATABASE IF NOT EXISTS login;
COMMIT;

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
COMMIT


CREATE INDEX idx_email ON login.usuarios(email);
CREATE INDEX idx_nivel_acesso ON login.usuarios(nivel_acesso);
CREATE INDEX idx_data_cadastro ON login.usuarios(data_cadastro);
GRANT ALL PRIVILEGES ON login.* TO 'loginuser'@'localhost' IDENTIFIED BY 'loginpass';
FLUSH PRIVILEGES;
COMMIT;


INSERT INTO login.usuarios (nome, sobrenome, email, sexo, senha, nivel_acesso) VALUES
('Admin', 'User', 'admin@admin.com', 'O', 'admin123', 'admin'),
('Regular', 'User', 'user@user.com', 'O', 'user123', 'user');
COMMIT;


