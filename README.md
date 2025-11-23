# Sistema de Login com PHP, Nginx e MySQL em Ambiente Docker

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
