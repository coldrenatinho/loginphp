# Sistema de Login em PHP com Docker

Este é um projeto completo de sistema de login desenvolvido em PHP, utilizando Bootstrap 5, JavaScript, CSS customizado, Nginx como servidor web e MySQL como banco de dados. Todo o ambiente é orquestrado com Docker e Docker Compose.

## 🚀 Funcionalidades

-   ✅ **Cadastro de Usuários**: Formulário completo com validações client-side e server-side
-   ✅ **Login de Usuários**: Autenticação segura com gerenciamento de sessões
-   ✅ **Recuperação de Senha**: Sistema de redefinição de senha
-   ✅ **Dashboard Administrativo**: Área protegida com informações do usuário
-   ✅ **Gerenciamento de Usuários**: Interface para administradores
-   ✅ **Bloqueio de Usuários**: Admins podem bloquear/desbloquear usuários
-   ✅ **Auditoria de Login**: Registro automático de IP, navegador, SO e dispositivo
-   ✅ **Histórico de Acessos**: Visualização dos últimos 50 acessos de cada usuário
-   🔐 **Segurança**: Senhas criptografadas com `password_hash()` e `password_verify()`
-   📱 **Design Responsivo**: Interface moderna com Bootstrap 5
-   ⚡ **Validações em Tempo Real**: JavaScript para feedback instantâneo
-   🐳 **Ambiente Containerizado**: Fácil de configurar e rodar com Docker

## 💻 Tecnologias Utilizadas

### Frontend
-   **HTML5**: Estrutura semântica
-   **CSS3**: Estilos customizados com variáveis CSS
-   **Bootstrap 5.3**: Framework CSS responsivo
-   **JavaScript (Vanilla)**: Validações e interatividade
-   **Font Awesome 6.4**: Ícones
-   **Google Fonts (Nunito)**: Tipografia moderna

### Backend
-   **PHP 8.x**: Lógica de servidor
-   **MySQL 8.0**: Banco de dados relacional
-   **Nginx**: Servidor web
-   **Docker & Docker Compose**: Containerização

## 📋 Pré-requisitos

Para executar este projeto, você precisará ter instalado em sua máquina:

-   [Docker](https://www.docker.com/get-started)
-   [Docker Compose](https://docs.docker.com/compose/install/)

## 🔧 Como Executar o Projeto

Siga os passos abaixo para configurar e iniciar o ambiente de desenvolvimento.

1.  **Clone o Repositório**
    ```bash
    git clone https://github.com/coldrenatinho/loginphp.git
    cd loginphp
    ```

2.  **Inicie os Contêineres**
    Execute o comando abaixo na raiz do projeto para construir e iniciar os serviços:
    ```bash
    docker-compose up -d
    ```
    O comando `-d` (detached) executa os contêineres em segundo plano.

3.  **Acesse a Aplicação**
    Após os contêineres estarem em execução, acesse no navegador:
    -   **Página Inicial**: [http://localhost/](http://localhost/)
    -   **Login**: [http://localhost/login](http://localhost/login)
    -   **Cadastro**: [http://localhost/cadastro](http://localhost/cadastro)
    -   **Recuperar Senha**: [http://localhost/esqueceuasenha](http://localhost/esqueceuasenha)

## 🔑 Credenciais de Teste

O sistema vem com usuários pré-cadastrados para teste:

**Administrador:**
- Email: `admin@admin.com`
- Senha: `admin123`

**Usuário Regular:**
- Email: `user@user.com`
- Senha: `user123`

## 🗄️ Gerenciamento do Banco de Dados

O projeto inclui **phpMyAdmin** para gerenciamento visual do banco de dados.

-   **URL**: [http://localhost:8080](http://localhost:8080)
-   **Servidor**: `db`
-   **Usuário**: `root`
-   **Senha**: `rootpassword`

## 📁 Estrutura do Projeto

```
.
├── compose.yml                    # Docker Compose configuration
├── Dockerfile                     # PHP Docker image
├── nginx.conf                     # Nginx configuration
├── README.md                      # Este arquivo
├── DOCUMENTACAO_TECNICA.md       # Documentação técnica completa
├── scripts/                       # SQL scripts
│   ├── create-phpmyadmin-db.sql
│   └── create-table-login.sql
└── src/
    ├── css/
    │   └── style.css             # Estilos customizados
    ├── js/
    │   └── validation.js         # Validações JavaScript
    └── php/                      # Código-fonte PHP
        ├── admin.php             # Dashboard (protegido)
        ├── cadastro.php          # Página de cadastro
        ├── config.php            # Configuração do banco
        ├── esqueceuasenha.php    # Recuperação de senha
        ├── index.php             # Página inicial
        ├── login.php             # Página de login
        ├── logout.php            # Logout
        └── protect.php           # Proteção de páginas
```

## 🔒 Recursos de Segurança

-   ✅ **Prepared Statements**: Proteção contra SQL Injection
-   ✅ **Password Hashing**: Senhas criptografadas com `password_hash()`
-   ✅ **Validações Duplas**: Client-side (JavaScript) e Server-side (PHP)
-   ✅ **Sanitização de Inputs**: Proteção contra XSS
-   ✅ **Sessões Seguras**: Gerenciamento adequado de sessões
-   ✅ **Índices no Banco**: Otimização de consultas

## 📊 Funcionalidades Detalhadas

### Página de Cadastro
- Campos: Nome, Sobrenome, Email, Sexo, Senha, Confirmar Senha
- Validação de formato de email
- Verificação de senha forte (indicador visual)
- Confirmação de senha
- Verificação de email duplicado
- Feedback visual em tempo real

### Página de Login
- Autenticação com email e senha
- Verificação com `password_verify()`
- Criação de sessão segura
- Redirecionamento para área administrativa
- Link para recuperação de senha

### Dashboard Administrativo
- Acesso restrito (apenas usuários autenticados)
- Informações do perfil
- Estatísticas do sistema
- Cards informativos
- Menu de navegação
- Logout seguro

### Recuperação de Senha
- Validação de email
- Geração de senha temporária
- Atualização segura no banco de dados
- Feedback ao usuário

## 🛠️ Comandos Úteis

**Iniciar o projeto:**
```bash
docker-compose up -d
```

**Parar o projeto:**
```bash
docker-compose down
```

**Ver logs:**
```bash
docker-compose logs -f
```

**Reiniciar serviços:**
```bash
docker-compose restart
```

**Acessar container PHP:**
```bash
docker-compose exec php bash
```

## 📖 Documentação Adicional

Para informações técnicas detalhadas, consulte:
- [DOCUMENTACAO_TECNICA.md](./DOCUMENTACAO_TECNICA.md) - Documentação técnica completa do projeto

## 🎓 Sobre o Projeto

Este projeto foi desenvolvido como trabalho acadêmico para demonstrar:
- Desenvolvimento Web Full Stack
- PHP e MySQL
- Segurança em Aplicações Web
- Design Responsivo com Bootstrap
- Boas Práticas de Programação
- Uso de Docker para desenvolvimento

**Referência:** Jon Duckett - Capítulo 16

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

**Renato Araújo**

### 📱 Contatos

- 📧 Email: [araujorenato045@gmail.com](mailto:araujorenato045@gmail.com)
- 🎥 YouTube: [@coldrenatinho](https://www.youtube.com/@coldrenatinho)
- 📸 Instagram: [@renato.gcc](https://www.instagram.com/renato.gcc/)
- 💻 GitHub: [coldrenatinho](https://github.com/coldrenatinho)

---

**Data de Entrega:** 25/11/2025



![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php)
![Nginx](https://img.shields.io/badge/Nginx-alpine-green?style=for-the-badge&logo=nginx)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql)
![Docker](https://img.shields.io/badge/Docker-Compose-blue?style=for-the-badge&logo=docker)

Este projeto demonstra a implementação de um sistema de autenticação de usuários utilizando PHP, com persistência de dados em um banco de dados MySQL. Toda a arquitetura é orquestrada com Docker Compose, garantindo um ambiente de desenvolvimento coeso, portátil e facilmente replicável.

O Nginx atua como servidor web e proxy reverso, direcionando o tráfego para a aplicação PHP e para o painel de gerenciamento do banco de dados, o phpMyAdmin.

---

## 🚀 Visão Geral da Arquitetura

A estrutura do projeto foi desenhada para isolar responsabilidades em contêineres distintos, promovendo uma arquitetura baseada em micro-serviços:

-   **`nginx`**: Ponto de entrada único (`Single Point of Entry`). Recebe todas as requisições na porta `8080` e atua como:
    -   **Servidor Web** para a aplicação PHP.
    -   **Proxy Reverso** para o serviço do phpMyAdmin, unificando o acesso sob o mesmo domínio e porta.
-   **`php`**: Contém o interpretador PHP-FPM, responsável por processar a lógica da aplicação.
-   **`mysql`**: Serviço de banco de dados para armazenamento de usuários e outras informações.
-   **`phpmyadmin`**: Ferramenta de administração do banco de dados, acessível através do proxy reverso do Nginx.

Este design não apenas organiza o projeto, mas também demonstra a habilidade de configurar e integrar diferentes tecnologias em um ambiente Docker complexo.

---

## ✨ Features e Soluções Implementadas

Durante o desenvolvimento e configuração deste ambiente, foram aplicadas diversas soluções para garantir estabilidade e funcionalidade:

1.  **Proxy Reverso com Nginx**: Centralização do acesso à aplicação e ao phpMyAdmin na porta `8080`, simplificando a interface para o usuário final e a configuração de rede.
2.  **Resolução de Conflitos de Versão**:
    -   **phpMyAdmin**: A imagem foi fixada na tag `5.2.0` para contornar um problema de instabilidade na tag `latest`, que resultava em loops de reinicialização.
    -   **MySQL**: Implementada uma rotina de reinicialização do volume de dados (`docker volume rm`) para resolver conflitos de "downgrade" do banco de dados, garantindo que o ambiente possa ser recriado do zero de forma consistente.
3.  **Correção de Permissões e Acesso (Erro 403)**: Ajuste da diretiva `root` no Nginx para apontar para o diretório correto da aplicação (`/var/www/html/php`), solucionando erros de "Forbidden".
4.  **Comunicação Inter-Contêineres**: Configuração das variáveis de ambiente (`PMA_HOST`, `PMA_PASSWORD`) para garantir a comunicação segura e bem-sucedida entre o phpMyAdmin e o MySQL.

---

## 🛠️ Como Executar o Projeto

Siga os passos abaixo para clonar e iniciar o ambiente completo.

**Pré-requisitos:**
*   [Docker](https://www.docker.com/get-started)
*   [Docker Compose](https://docs.docker.com/compose/install/)

**1. Clone o Repositório**
```bash
git clone https://github.com/coldrenatinho/loginphp.git
cd loginphp
```

**2. Inicie os Serviços**
Execute o comando abaixo para construir as imagens e iniciar todos os contêineres em segundo plano:
```bash
docker compose up -d --build
```
O `--build` é importante na primeira vez para construir a imagem do PHP com as extensões necessárias.

**3. Verifique o Status**
Após alguns segundos, verifique se todos os contêineres estão rodando:
```bash
docker compose ps
```
Você deverá ver os serviços `nginx`, `php`, `mysql` e `phpmyadmin` com o status `Up`.

---

## 🌐 Acesso e Credenciais

Com os contêineres em execução, acesse os seguintes endereços no seu navegador:

-   **Aplicação (Tela de Login)**
    -   **URL:** [http://localhost:8080/](http://localhost:8080/)

-   **phpMyAdmin (Gerenciador do Banco de Dados)**
    -   **URL:** [http://localhost:8080/phpmyadmin/](http://localhost:8080/phpmyadmin/)
    -   **Usuário:** `root`
    -   **Senha:** `rootpassword`

---

## 🧰 Comandos Úteis para Desenvolvimento

-   **Visualizar Logs em Tempo Real (ex: Nginx):**
    ```bash
    docker compose logs -f nginx
    ```

-   **Parar e Remover os Contêineres:**
    ```bash
    docker compose down
    ```

-   **Recriar o Banco de Dados do Zero:**
    *Se o MySQL apresentar problemas ou você quiser um ambiente limpo.*
    ```bash
    docker compose down
    docker volume rm loginphp_mysqldata
    docker compose up -d
    ```
