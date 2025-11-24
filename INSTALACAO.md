# 🚀 Guia de Instalação e Configuração

## Instalação Rápida

### 1. Requisitos do Sistema

Antes de começar, certifique-se de ter instalado:

- **Docker** (versão 20.10 ou superior)
  - Windows: https://docs.docker.com/desktop/install/windows-install/
  - Mac: https://docs.docker.com/desktop/install/mac-install/
  - Linux: https://docs.docker.com/engine/install/

- **Docker Compose** (geralmente vem com o Docker Desktop)

### 2. Clone o Repositório

```bash
git clone https://github.com/coldrenatinho/loginphp.git
cd loginphp
```

### 3. Inicie o Ambiente

```bash
docker-compose up -d
```

Aguarde alguns segundos enquanto os containers são criados e iniciados.

### 4. Verifique se está Funcionando

Abra seu navegador e acesse:
- http://localhost - Deve redirecionar para a página de login
- http://localhost:8080 - phpMyAdmin

### 5. Faça Login

Use as credenciais padrão:
- **Email:** admin@admin.com
- **Senha:** admin123

---

## Solução de Problemas Comuns

### Problema: Porta 80 já está em uso

**Sintoma:** Erro ao iniciar o Nginx

**Solução:**
1. Abra o arquivo `compose.yml`
2. Localize a linha `ports: - "80:80"`
3. Altere para `ports: - "8000:80"` (ou outra porta livre)
4. Acesse a aplicação em `http://localhost:8000`

### Problema: Containers não iniciam

**Solução:**
```bash
# Pare todos os containers
docker-compose down

# Limpe os volumes
docker-compose down -v

# Reconstrua e inicie
docker-compose up -d --build
```

### Problema: Erro de conexão com banco de dados

**Solução:**
1. Verifique se o container do MySQL está rodando:
   ```bash
   docker-compose ps
   ```

2. Se necessário, recrie os containers:
   ```bash
   docker-compose down
   docker-compose up -d
   ```

### Problema: Página em branco ou erro 500

**Solução:**
1. Verifique os logs do PHP:
   ```bash
   docker-compose logs php
   ```

2. Verifique as permissões dos arquivos:
   ```bash
   sudo chmod -R 755 src/
   ```

---

## Configuração Personalizada

### Alterar Credenciais do Banco de Dados

1. Edite o arquivo `compose.yml`:
```yaml
db:
  environment:
    MYSQL_ROOT_PASSWORD: sua_nova_senha
    MYSQL_DATABASE: login
```

2. Edite o arquivo `src/php/config.php`:
```php
$senha = "sua_nova_senha";
```

3. Recrie os containers:
```bash
docker-compose down -v
docker-compose up -d
```

### Adicionar Novos Usuários Administradores

1. Acesse o phpMyAdmin (http://localhost:8080)
2. Faça login com:
   - Servidor: `db`
   - Usuário: `root`
   - Senha: `rootpassword`

3. Navegue até a tabela `login.usuarios`
4. Clique em "Inserir"
5. Preencha os campos (use `password_hash()` para a senha)

Ou use o formulário de cadastro e depois altere o `nivel_acesso` para `admin` no banco.

---

## Atualizações e Manutenção

### Atualizar o Código

```bash
# Puxe as alterações
git pull origin main

# Reconstrua os containers
docker-compose up -d --build
```

### Backup do Banco de Dados

```bash
# Exportar
docker-compose exec db mysqldump -u root -prootpassword login > backup.sql

# Importar
docker-compose exec -T db mysql -u root -prootpassword login < backup.sql
```

### Ver Logs em Tempo Real

```bash
# Todos os serviços
docker-compose logs -f

# Apenas PHP
docker-compose logs -f php

# Apenas MySQL
docker-compose logs -f db
```

---

## Desenvolvimento

### Modificar o Código

Os arquivos estão mapeados como volumes, então qualquer alteração nos arquivos em `src/` será refletida imediatamente.

### Adicionar Dependências PHP

1. Entre no container:
```bash
docker-compose exec php bash
```

2. Instale as dependências com Composer (se necessário):
```bash
composer require nome/do/pacote
```

### Executar Comandos SQL

```bash
docker-compose exec db mysql -u root -prootpassword login -e "SELECT * FROM usuarios;"
```

---

## Deploy em Produção

⚠️ **IMPORTANTE:** Este projeto está configurado para desenvolvimento. Para produção:

1. **Altere todas as senhas padrão**
2. **Configure HTTPS** (Let's Encrypt/Certbot)
3. **Desabilite o phpMyAdmin** ou proteja com senha forte
4. **Configure envio de email** (PHPMailer)
5. **Aumente a segurança do MySQL** (desabilite acesso remoto)
6. **Use variáveis de ambiente** para senhas sensíveis
7. **Configure backups automáticos**
8. **Monitore os logs**

---

## Recursos Adicionais

### Documentação
- [README.md](./README.md) - Documentação geral do projeto
- [DOCUMENTACAO_TECNICA.md](./DOCUMENTACAO_TECNICA.md) - Detalhes técnicos

### Suporte
- Abra uma issue no GitHub: https://github.com/coldrenatinho/loginphp/issues
- Email: [seu-email@exemplo.com]

---

✅ **Tudo pronto!** Seu sistema de login está configurado e funcionando.
