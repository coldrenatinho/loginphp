# Sistema de Auditoria e Bloqueio de Usuários

## Alterações Implementadas

### 1. Auditoria de Login
O sistema agora registra automaticamente todas as tentativas de login com as seguintes informações:
- **Data e hora** do acesso
- **Endereço IP** de origem
- **User Agent** completo do navegador
- **Sistema Operacional** (Windows, macOS, Linux, Android, iOS)
- **Tipo de Dispositivo** (Desktop, Mobile, Tablet)
- **Status** do login (sucesso ou falha)

### 2. Bloqueio de Usuários
Administradores podem bloquear/desbloquear usuários com:
- **Motivo do bloqueio** obrigatório
- **Mensagem personalizada** para usuários bloqueados
- **Modal informativo** exibido no login
- **Impossibilidade de autoadministrar** (admin não pode bloquear a si mesmo)

### 3. Visualização de Histórico
- **Modal interativo** com histórico de acessos
- **Últimos 50 acessos** de cada usuário
- **Informações detalhadas**: IP, dispositivo, SO, navegador
- **Carregamento AJAX** para melhor performance

## Como Aplicar as Alterações

### Opção 1: Banco de Dados Novo
Se você está criando o banco de dados pela primeira vez, execute:

```bash
docker exec -i loginphp-mysql-1 mysql -uroot -psenha123 < scripts/create-table-login.sql
```

### Opção 2: Banco de Dados Existente
Se você já tem o banco de dados criado, execute apenas a migração:

```bash
docker exec -i loginphp-mysql-1 mysql -uroot -psenha123 < scripts/migration-auditoria.sql
```

## Verificação

Após executar o script, verifique se as alterações foram aplicadas:

```bash
docker exec -it loginphp-mysql-1 mysql -uroot -psenha123 -e "USE login; DESCRIBE usuarios; SHOW TABLES;"
```

Você deve ver:
- Coluna `bloqueado` na tabela `usuarios`
- Coluna `motivo_bloqueio` na tabela `usuarios`
- Tabela `auditoria_login` criada

## Funcionalidades

### Para Administradores
1. Acesse **Gerenciar Usuários** no menu
2. Cada usuário terá 2 botões de ação:
   - **Histórico** (ícone relógio): Ver últimos acessos
   - **Bloquear/Desbloquear** (ícone ban/check): Gerenciar bloqueio

### Para Usuários Bloqueados
Ao tentar fazer login, será exibido:
- Modal informativo com o motivo do bloqueio
- Informações de contato do administrador
- Impossibilidade de acessar o sistema

## Estrutura de Arquivos Alterados

```
src/php/
├── login.php              # Verificação de bloqueio e registro de auditoria
├── usuarios.php           # Gerenciamento de bloqueio e visualização
└── auditoria_ajax.php     # Endpoint para carregar histórico (NOVO)

scripts/
├── create-table-login.sql # Atualizado com novas tabelas/colunas
└── migration-auditoria.sql # Script de migração (NOVO)
```

## Segurança

✅ **Prepared Statements** em todas as queries
✅ **Validação server-side** de todos os campos
✅ **Proteção contra SQL Injection**
✅ **Sanitização de saída** com htmlspecialchars()
✅ **Verificação de permissões** (apenas admins podem bloquear)
✅ **Autopreservação** (admin não pode se autobloquear)

## Tecnologias Utilizadas

- **PHP 8.x**: Backend e lógica de negócio
- **MySQL 8.0**: Armazenamento de dados
- **Bootstrap 5.3**: Interface responsiva
- **JavaScript Vanilla**: AJAX e interações
- **Font Awesome 6.4**: Ícones
