-- =====================================================
-- Script de Migração - Sistema de Auditoria
-- Adiciona campos para auditoria de login e bloqueio
-- =====================================================

-- Cria tabela de auditoria de login
CREATE TABLE IF NOT EXISTS auditoria_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL COMMENT 'ID do usuário que fez login',
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do login',
    ip_origem VARCHAR(45) NOT NULL COMMENT 'Endereço IP de origem (IPv4 ou IPv6)',
    navegador VARCHAR(255) NULL COMMENT 'User Agent do navegador',
    sistema_operacional VARCHAR(100) NULL COMMENT 'Sistema operacional extraído do User Agent',
    dispositivo VARCHAR(100) NULL COMMENT 'Tipo de dispositivo (Desktop, Mobile, Tablet)',
    sucesso TINYINT(1) DEFAULT 1 COMMENT 'Login bem-sucedido (0=Não, 1=Sim)',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_data_hora (data_hora),
    INDEX idx_ip_origem (ip_origem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Auditoria de tentativas de login';

COMMIT;
