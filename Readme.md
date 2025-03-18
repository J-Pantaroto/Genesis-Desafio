# Documentação do Projeto

## Visão Geral
Este projeto tem como objetivo implementar um sistema de controle de viagens utilizando Laravel e PostgreSQL. O ambiente de desenvolvimento é baseado em Docker para garantir portabilidade e consistência entre diferentes máquinas.

## Requisitos
- **PostgreSQL 14+**
- **Laravel**
- **Docker e Docker Compose**

## Configuração do Ambiente

1. **Clone o repositório**:
   ```sh
   git clone <https://github.com/J-Pantaroto/Genesis-Desafio.git>
   
   ```
2. **Va para o diretorio Genesis-Desafio**:
   ```sh
   cd Genesis-Desafio
   ```
3. **Construa a imagem do Docker**:
   ```sh
   docker-compose build
   ```

4. **Suba os containers do Docker**:
   ```sh
   docker-compose up -d
   ```
5. **Acesse no navegador**:
   ```sh
   localhost:8000
   ```
## Modificações no `docker-compose.yml`

O arquivo `docker-compose.yml` original foi modificado para incluir:
- **Volume persistente para o banco de dados** para evitar perda de dados entre reinicializações.
- **Definição de network personalizada** para melhor isolamento dos containers.
- **Adicionada a aplicação Laravel no ambiente Docker**.
- **Healthcheck no PostgreSQL** para garantir que o banco de dados esteja pronto antes do Laravel tentar conectar.
- **Configuração do Nginx** como servidor web reverso para servir a aplicação Laravel.

### Explicação das Modificações

#### **Healthcheck no PostgreSQL**
O healthcheck foi adicionado para evitar que o Laravel tente executar migrações antes que o PostgreSQL esteja completamente inicializado. Como o PostgreSQL pode demorar alguns segundos para subir, sem essa verificação o Laravel pode falhar ao tentar se conectar ao banco de dados.

```yaml
healthcheck:
  test: [ "CMD-SHELL", "pg_isready -U postgres -h localhost" ]
  interval: 10s
  retries: 10
  timeout: 5s
```

#### **Uso do Nginx**
O Nginx foi adicionado ao ambiente Docker para atuar como servidor web, servindo as requisições para a aplicação Laravel de forma mais eficiente. Ele melhora a performance e gerencia requisições HTTP corretamente, evitando a necessidade de rodar `php artisan serve`. Ele também facilita a configuração de SSL e otimizações futuras.

#### **Uso do Dockerfile**
O Dockerfile foi configurado para otimizar a instalação do Laravel e automatizar configurações essenciais:
- Instala as extensões PHP necessárias (`pdo`, `pdo_pgsql`, `zip`).
- Copia o Composer para dentro do container e executa `composer install` para instalar as dependências.
- Ajusta permissões das pastas `storage` e `bootstrap/cache` para garantir que o Laravel possa escrever nesses diretórios.
- Expõe a porta `9000` para comunicação do PHP-FPM.
- Adiciona um comando final (`CMD`) que:
  1. Copia o `.env.example` para `.env`, caso ainda não exista.
  2. Gera a chave de criptografia do Laravel (`php artisan key:generate`).
  3. Executa as migrações do banco de dados (`php artisan migrate --force`), garantindo que as tabelas estejam sempre atualizadas ao iniciar o container.

### Novo `docker-compose.yml`

```yaml
services:
  postgres:
    image: postgres:14
    container_name: entrevista_postgres
    environment:
      POSTGRES_USER: postgres
      POSTGRES_PASSWORD: "postgres"
      POSTGRES_DB: entrevista
    ports:
      - "5432:5432"
    networks:
      - genesis_network
    volumes:
      - /etc/localtime:/etc/localtime:ro
      - ./storage/database:/var/lib/postgresql/data
    healthcheck:
      test: [ "CMD-SHELL", "pg_isready -U postgres -h localhost" ]
      interval: 10s
      retries: 10
      timeout: 5s

  app:
    build: .
    container_name: genesis_app
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
      - /var/www/html/vendor/
    networks:
      - genesis_network
    depends_on:
      postgres:
        condition: service_healthy

  web:
    image: nginx:latest
    container_name: genesis_nginx
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    networks:
    - genesis_network
    depends_on:
      - app

networks:
  genesis_network:
    driver: bridge
```

## Executando a Aplicação

Acesse:
```
http://localhost:8000
```

## Considerações Finais
- Certifique-se de que os containers do Docker estão rodando antes de iniciar a aplicação.
- Verifique permissões de arquivos e pastas, caso encontre erros de escrita.


