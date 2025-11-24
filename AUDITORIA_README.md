# Sistema de Auditoria e Bloqueio

## O que foi implementado?

✅ **Auditoria de Login**: Registra data, hora, IP, navegador, SO e dispositivo de todos os acessos  
✅ **Bloqueio de Usuários**: Admins podem bloquear/desbloquear com motivo obrigatório  
✅ **Histórico de Acessos**: Modal com últimos 50 acessos de cada usuário  

## Como aplicar no banco de dados?

**Banco novo:**
```bash
docker exec -i mysql_login_php mysql -uroot -prootpassword < scripts/create-table-login.sql
```

**Banco existente (migração):**
```bash
docker exec -i mysql_login_php mysql -uroot -prootpassword < scripts/migration-auditoria.sql
```

## Como usar?

**Administradores:**
- Acesse "Gerenciar Usuários"
- Use os botões: 🕐 (histórico) ou ⚠️ (bloquear) / ✅ (desbloquear)

**Usuários bloqueados:**
- Verão modal informativo ao tentar fazer login
- Com motivo do bloqueio e contato do admin

## Arquivos alterados

```
src/php/login.php           - Verifica bloqueio + registra auditoria
src/php/usuarios.php        - Interface de gerenciamento
src/php/auditoria_ajax.php  - Carrega histórico (NOVO)
scripts/migration-auditoria.sql - Migração do banco (NOVO)
```
