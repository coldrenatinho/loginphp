# Documentação Técnica - Sistema de Login PHP

## 📋 Índice
1. [Visão Geral](#visão-geral)
2. [Requisitos do Projeto](#requisitos-do-projeto)
3. [Arquitetura do Sistema](#arquitetura-do-sistema)
4. [Funcionalidades Implementadas](#funcionalidades-implementadas)
5. [Segurança](#segurança)
6. [Estrutura do Banco de Dados](#estrutura-do-banco-de-dados)
7. [Guia de Uso](#guia-de-uso)
8. [Tecnologias Utilizadas](#tecnologias-utilizadas)

---

## 🎯 Visão Geral

Sistema completo de autenticação e gerenciamento de usuários desenvolvido em PHP com MySQL, utilizando Bootstrap 5 para interface responsiva e moderna.

**Data de Entrega:** 25/11/2025

---

## 📝 Requisitos do Projeto

### Requisitos Funcionais
- ✅ Cadastro de novos usuários com validação completa
- ✅ Sistema de login com autenticação segura
- ✅ Recuperação de senha
- ✅ Área administrativa protegida
- ✅ Gerenciamento de sessões
- ✅ Validações client-side e server-side

### Requisitos Técnicos
- ✅ PHP para lógica de backend
- ✅ MySQL para armazenamento de dados
- ✅ Bootstrap 5 para interface responsiva
- ✅ JavaScript para validações client-side
- ✅ CSS customizado para estilização
- ✅ Prepared Statements para segurança
- ✅ Password hashing com `password_hash()`
- ✅ Índices de banco de dados para otimização

---

## 🏗️ Arquitetura do Sistema

### Estrutura de Arquivos

```
loginphp/
├── src/
│   ├── css/
│   │   └── style.css              # Estilos customizados
│   ├── js/
│   │   └── validation.js          # Validações JavaScript
│   └── php/
│       ├── admin.php              # Página administrativa (protegida)
│       ├── cadastro.php           # Formulário de cadastro
│       ├── config.php             # Configuração do banco de dados
│       ├── esqueceuasenha.php     # Recuperação de senha
│       ├── index.php              # Página inicial
│       ├── login.php              # Formulário de login
│       ├── logout.php             # Encerramento de sessão
│       └── protect.php            # Proteção de páginas
├── scripts/
│   ├── create-phpmyadmin-db.sql   # Script para phpMyAdmin
│   └── create-table-login.sql     # Criação de tabelas
├── compose.yml                    # Docker Compose
├── Dockerfile                     # Dockerfile PHP
├── nginx.conf                     # Configuração Nginx
└── README.md                      # Documentação principal
```

---

## ⚙️ Funcionalidades Implementadas

### 1. Página de Cadastro (`cadastro.php`)
**Campos do Formulário:**
- Nome completo (mínimo 3 caracteres)
- Sobrenome (mínimo 2 caracteres)
- Email (validação de formato)
- Sexo (Masculino, Feminino, Outro)
- Senha (mínimo 6 caracteres)
- Confirmação de senha

**Validações Implementadas:**
- ✅ Validação client-side com JavaScript
- ✅ Validação server-side com PHP
- ✅ Verificação de email duplicado
- ✅ Verificação de força da senha
- ✅ Confirmação de senha
- ✅ Feedback visual em tempo real

**Segurança:**
- Prepared statements
- Password hashing com `password_hash()`
- Sanitização de inputs
- Proteção contra SQL Injection

---

### 2. Página de Login (`login.php`)
**Campos do Formulário:**
- Email
- Senha

**Funcionalidades:**
- ✅ Autenticação com `password_verify()`
- ✅ Criação de sessão após login bem-sucedido
- ✅ Redirecionamento para área administrativa
- ✅ Mensagens de erro descritivas
- ✅ Link para recuperação de senha
- ✅ Link para cadastro

**Sessão Criada:**
```php
$_SESSION['usuario']       // ID do usuário
$_SESSION['nome']          // Nome do usuário
$_SESSION['email']         // Email do usuário
$_SESSION['nivel_acesso']  // admin ou user
```

---

### 3. Página Administrativa (`admin.php`)
**Características:**
- ✅ Acesso restrito (protegida por `protect.php`)
- ✅ Dashboard com estatísticas
- ✅ Informações do perfil do usuário
- ✅ Cards informativos
- ✅ Menu de navegação
- ✅ Botão de logout

**Informações Exibidas:**
- Nome completo do usuário
- Email
- Nível de acesso
- Data de cadastro
- Total de usuários no sistema
- Status da conta

---

### 4. Recuperação de Senha (`esqueceuasenha.php`)
**Funcionalidades:**
- ✅ Validação de email
- ✅ Geração de senha temporária
- ✅ Atualização da senha no banco
- ✅ Exibição da nova senha (em produção seria enviada por email)

**Observação:**
Em ambiente de produção, a senha deve ser enviada por email usando bibliotecas como PHPMailer. No ambiente de desenvolvimento, a senha é exibida na tela.

---

## 🔒 Segurança

### Medidas Implementadas

1. **Proteção contra SQL Injection**
   - Uso exclusivo de Prepared Statements
   - Sanitização de inputs com `trim()`
   - Validação de tipos de dados

2. **Proteção de Senhas**
   ```php
   // Ao cadastrar
   $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
   
   // Ao fazer login
   password_verify($senha_digitada, $senha_hash_banco);
   ```

3. **Proteção contra XSS**
   - Uso de `htmlspecialchars()` em todas as saídas
   - Atributo `ENT_QUOTES` para proteção de aspas

4. **Validações**
   - Client-side: JavaScript (UX)
   - Server-side: PHP (Segurança)
   - Dupla camada de proteção

5. **Sessões Seguras**
   - Verificação de autenticação em páginas protegidas
   - Destruição completa da sessão no logout
   - Tempo de vida configurável

---

## 🗄️ Estrutura do Banco de Dados

### Tabela: `usuarios`

| Campo | Tipo | Descrição | Índice |
|-------|------|-----------|--------|
| `id` | INT | Identificador único (PK) | PRIMARY |
| `nome` | VARCHAR(100) | Nome do usuário | - |
| `sobrenome` | VARCHAR(100) | Sobrenome do usuário | - |
| `email` | VARCHAR(150) | Email único | UNIQUE, INDEX |
| `sexo` | ENUM('M','F','O') | Sexo do usuário | - |
| `senha` | VARCHAR(255) | Hash da senha | - |
| `nivel_acesso` | ENUM('admin','user') | Nível de acesso | INDEX |
| `data_cadastro` | TIMESTAMP | Data de registro | INDEX |
| `ultima_atualizacao` | TIMESTAMP | Última atualização | - |

### Índices Criados

```sql
CREATE INDEX idx_email ON usuarios(email);
CREATE INDEX idx_nivel_acesso ON usuarios(nivel_acesso);
CREATE INDEX idx_data_cadastro ON usuarios(data_cadastro);
```

**Benefícios dos Índices:**
- Buscas por email mais rápidas
- Filtragem por nível de acesso otimizada
- Ordenação por data eficiente

---

## 📚 Guia de Uso

### 1. Iniciar o Ambiente
```bash
docker-compose up -d
```

### 2. Acessar a Aplicação
- **Página Principal:** http://localhost/
- **Login:** http://localhost/login.php
- **Cadastro:** http://localhost/cadastro.php
- **phpMyAdmin:** http://localhost:8080

### 3. Credenciais de Teste
**Administrador:**
- Email: `admin@admin.com`
- Senha: `admin123`

**Usuário Regular:**
- Email: `user@user.com`
- Senha: `user123`

### 4. Fluxo de Uso

```
1. Acesse /cadastro.php
   ↓
2. Preencha o formulário
   ↓
3. Clique em "Cadastrar"
   ↓
4. Acesse /login.php
   ↓
5. Faça login com suas credenciais
   ↓
6. Será redirecionado para /admin.php (área protegida)
```

---

## 💻 Tecnologias Utilizadas

### Backend
- **PHP 8.x**
  - Prepared Statements (MySQLi)
  - `password_hash()` e `password_verify()`
  - Sessões
  - Validações

### Frontend
- **HTML5**
  - Semântica moderna
  - Formulários acessíveis
  
- **CSS3**
  - Flexbox e Grid
  - Variáveis CSS
  - Animações
  - Media queries (responsivo)

- **Bootstrap 5.3**
  - Sistema de grid responsivo
  - Componentes (cards, alerts, forms)
  - Utilitários
  
- **JavaScript (Vanilla)**
  - Validações client-side
  - Indicador de força de senha
  - Auto-hide de alertas
  - Manipulação do DOM

- **Font Awesome 6.4**
  - Ícones vetoriais

- **Google Fonts (Nunito)**
  - Tipografia moderna

### Banco de Dados
- **MySQL 8.0**
  - InnoDB engine
  - UTF-8 (utf8mb4)
  - Índices otimizados

### DevOps
- **Docker**
  - Container PHP
  - Container Nginx
  - Container MySQL
  - Container phpMyAdmin
  
- **Docker Compose**
  - Orquestração de containers
  - Volumes persistentes

### Servidor Web
- **Nginx**
  - Proxy reverso
  - Configuração de FastCGI

---

## 📊 Checklist de Qualidade

### Funcionalidade
- [x] Cadastro de usuários funcionando
- [x] Login funcionando
- [x] Logout funcionando
- [x] Recuperação de senha funcionando
- [x] Área administrativa protegida
- [x] Sessões gerenciadas corretamente

### Código
- [x] Código bem estruturado
- [x] Comentários explicativos
- [x] Uso de prepared statements
- [x] Validações client e server-side
- [x] Tratamento de erros
- [x] Sanitização de dados

### Design
- [x] Interface responsiva
- [x] Design moderno e profissional
- [x] Feedback visual ao usuário
- [x] Acessibilidade
- [x] Consistência visual

### Segurança
- [x] Senhas criptografadas
- [x] Proteção contra SQL Injection
- [x] Proteção contra XSS
- [x] Validação de inputs
- [x] Páginas protegidas

---

## 🎓 Referências

- **Livro:** Jon Duckett - Capítulo 16
- **Bootstrap 5:** https://getbootstrap.com/docs/5.3/
- **PHP Manual:** https://www.php.net/manual/pt_BR/
- **MySQL Documentation:** https://dev.mysql.com/doc/

---

## 👨‍💻 Desenvolvedor

Projeto desenvolvido como trabalho acadêmico para demonstrar conhecimentos em:
- Desenvolvimento Web Full Stack
- PHP e MySQL
- Segurança em Aplicações Web
- Design Responsivo
- Boas Práticas de Programação

---

**Data de Conclusão:** Novembro de 2025
**Entrega:** 25/11/2025
