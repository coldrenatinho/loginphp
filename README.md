# loginphp
Página para a matéria de desenvolvimento web criando uma página de login do usuário utilizando PHP e persistência de dados

## phpMyAdmin

O projeto inclui um serviço phpMyAdmin disponível localmente em http://localhost:8080 (mapeado para a porta 80 do container). Se a porta não responder, rode:

```bash
docker compose up -d phpmyadmin
```

Observação: foi fixada a imagem `phpmyadmin/phpmyadmin:5.2.0` no `compose.yml` porque a tag `latest` que estava sendo usada vinha com o binário de inicialização vazio em algumas pulls; pinagem da versão evita esse problema até que `latest` seja novamente confiável.
