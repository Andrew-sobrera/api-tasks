# 📝 API de Gerenciamento de Tarefas (Tasks)

Uma API RESTful robusta desenvolvida com Laravel 11, seguindo princípios de Clean Architecture e boas práticas de desenvolvimento.

## 📋 Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias](#tecnologias)
- [Arquitetura](#arquitetura)
- [Pré-requisitos](#pré-requisitos)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Uso da API](#uso-da-api)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Exceções Customizadas](#exceções-customizadas)
- [Testes](#testes)
- [Documentação Adicional](#documentação-adicional)

---

## 🎯 Sobre o Projeto

Esta API foi desenvolvida para gerenciar tarefas (tasks) de forma eficiente e escalável. O projeto implementa:

- ✅ **CRUD completo** de tarefas
- ✅ **Arquitetura em camadas** (Controller → Service → Repository)
- ✅ **Exceções customizadas** com tratamento de erros
- ✅ **Validações robustas** de dados
- ✅ **Recursos API** (API Resources) para formatação de respostas
- ✅ **Docker** para ambiente consistente
- ✅ **MySQL** como banco de dados
- ✅ **Nginx** como servidor web

### Funcionalidades

- Criar novas tarefas
- Listar todas as tarefas
- Atualizar tarefas existentes
- Deletar tarefas
- Validação automática de dados
- Tratamento de erros com mensagens claras

---

## 🚀 Tecnologias

### Backend
- **PHP 8.2+**
- **Laravel 11.x**
- **MySQL 8.0**

### DevOps
- **Docker** & **Docker Compose**
- **Nginx** (Alpine)
- **PHP-FPM**

### Ferramentas
- **Composer** (gerenciador de dependências)
- **Artisan** (CLI do Laravel)
- **Make** (automação de comandos)

---

## 🏗️ Arquitetura

O projeto segue uma arquitetura em camadas para melhor organização e manutenibilidade:

```
┌─────────────────────────────────────────┐
│           HTTP Request                   │
└─────────────────┬───────────────────────┘
                  │
        ┌─────────▼─────────┐
        │   TaskController  │  ← Recebe requisições HTTP
        └─────────┬─────────┘
                  │
        ┌─────────▼─────────┐
        │    TaskService    │  ← Lógica de negócio e validações
        └─────────┬─────────┘
                  │
        ┌─────────▼─────────┐
        │  TaskRepository   │  ← Acesso ao banco de dados
        └─────────┬─────────┘
                  │
        ┌─────────▼─────────┐
        │    Task Model     │  ← Eloquent ORM
        └─────────┬─────────┘
                  │
        ┌─────────▼─────────┐
        │  MySQL Database   │
        └───────────────────┘
```

### Camadas

1. **Controller** (`TaskController`)
   - Recebe requisições HTTP
   - Retorna respostas JSON formatadas
   - Delega lógica para o Service

2. **Service** (`TaskService`)
   - Contém regras de negócio
   - Valida dados de entrada
   - Coordena operações

3. **Repository** (`TaskRepository`)
   - Abstrai acesso ao banco de dados
   - Isola queries SQL/Eloquent
   - Facilita testes e manutenção

4. **Model** (`Task`)
   - Representa a entidade no banco
   - Define relationships
   - Eloquent ORM

---

## 📦 Pré-requisitos

Antes de começar, certifique-se de ter instalado:

- [Docker](https://www.docker.com/get-started) (20.10+)
- [Docker Compose](https://docs.docker.com/compose/install/) (2.0+)
- [Git](https://git-scm.com/)
- [Make](https://www.gnu.org/software/make/) (opcional, mas recomendado)

---

## ⚙️ Instalação

```bash
# 1. Clone o repositório
git clone <url-do-repositorio>
cd api

# 2. Copie o arquivo de ambiente
cp .env.example .env

# 3. Construa e inicie os containers
docker-compose build
docker-compose up -d

# 4. Aguarde o MySQL inicializar (10-15 segundos)
sleep 15

# 5. Gere a chave da aplicação
docker-compose exec app php artisan key:generate

# 6. Execute as migrations
docker-compose exec app php artisan migrate

# 7. Configure permissões
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

---

## 🔧 Configuração

### Arquivo .env

O arquivo `.env` já vem configurado para usar o MySQL do Docker:

```env
# Aplicação
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Banco de Dados (Docker)
DB_CONNECTION=mysql
DB_HOST=db                    # Nome do serviço no Docker
DB_PORT=3306                  # Porta interna do container
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password
```

### Portas Utilizadas

| Serviço | Porta Externa | Porta Interna | Descrição |
|---------|---------------|---------------|-----------|
| Nginx   | 8000          | 80            | Servidor Web / API |
| MySQL   | 3307          | 3306          | Banco de Dados |
| PHP-FPM | -             | 9000          | PHP FastCGI |

### Serviços Docker

```yaml
app     - PHP 8.2-FPM (Container: laravel_app)
nginx   - Nginx Alpine (Container: laravel_nginx)
db      - MySQL 8.0 (Container: laravel_mysql)
```

---

## 🔌 Uso da API

### Base URL

```
http://localhost:8000/api
```

### Endpoints Disponíveis

#### 1. Listar Todas as Tarefas

```bash
GET /api/tasks
```

**Resposta:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Minha Tarefa",
      "description": "Descrição da tarefa",
      "status": 0,
      "created_at": "2024-10-14T12:00:00.000000Z",
      "updated_at": "2024-10-14T12:00:00.000000Z"
    }
  ]
}
```

#### 2. Buscar Tarefa Específica

```bash
GET /api/tasks/{id}
```

**Exemplo:**
```bash
curl http://localhost:8000/api/tasks/1
```

**Resposta (Sucesso - 200):**
```json
{
  "data": {
    "id": 1,
    "title": "Minha Tarefa",
    "description": "Descrição da tarefa",
    "status": 0,
    "created_at": "2024-10-14T12:00:00.000000Z",
    "updated_at": "2024-10-14T12:00:00.000000Z"
  }
}
```

**Resposta (Não Encontrada - 404):**
```json
{
  "error": "Task Not Found",
  "message": "Tarefa com ID 999 não encontrada."
}
```

#### 3. Criar Nova Tarefa

```bash
POST /api/tasks
Content-Type: application/json
```

**Body:**
```json
{
  "title": "Nova Tarefa",
  "description": "Descrição detalhada",
  "status": 0
}
```

**Exemplo:**
```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Minha Nova Tarefa",
    "description": "Fazer algo importante",
    "status": 0
  }'
```

**Campos:**
- `title` (obrigatório): Título da tarefa (máx. 255 caracteres)
- `description` (opcional): Descrição detalhada
- `status` (obrigatório): Status da tarefa
  - `0` = Pendente
  - `1` = Concluída

**Resposta (201 Created):**
```json
{
  "data": {
    "id": 2,
    "title": "Nova Tarefa",
    "description": "Descrição detalhada",
    "status": 0,
    "created_at": "2024-10-14T12:30:00.000000Z",
    "updated_at": "2024-10-14T12:30:00.000000Z"
  }
}
```

**Resposta (Erro de Validação - 422):**
```json
{
  "error": "Invalid Task Data",
  "message": "O título da tarefa é obrigatório."
}
```

#### 4. Atualizar Tarefa

```bash
PUT /api/tasks/{id}
Content-Type: application/json
```

**Body:**
```json
{
  "title": "Tarefa Atualizada",
  "description": "Nova descrição",
  "status": 1
}
```

**Exemplo:**
```bash
curl -X PUT http://localhost:8000/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Tarefa Concluída",
    "status": 1
  }'
```

**Resposta (200 OK):**
```json
{
  "data": {
    "id": 1,
    "title": "Tarefa Atualizada",
    "description": "Nova descrição",
    "status": 1,
    "created_at": "2024-10-14T12:00:00.000000Z",
    "updated_at": "2024-10-14T13:00:00.000000Z"
  }
}
```

#### 5. Deletar Tarefa

```bash
DELETE /api/tasks/{id}
```

**Exemplo:**
```bash
curl -X DELETE http://localhost:8000/api/tasks/1
```

**Resposta (200 OK):**
```json
{
  "message": "Task deleted successfully"
}
```

**Resposta (Não Encontrada - 404):**
```json
{
  "error": "Task Not Found",
  "message": "Tarefa com ID 1 não encontrada."
}
```

### Status HTTP

| Código | Descrição |
|--------|-----------|
| 200    | OK - Requisição bem-sucedida |
| 201    | Created - Recurso criado com sucesso |
| 404    | Not Found - Recurso não encontrado |
| 422    | Unprocessable Entity - Dados inválidos |
| 500    | Internal Server Error - Erro no servidor |

### Exemplos com curl

```bash
# Listar todas as tarefas
curl http://localhost:8000/api/tasks

# Criar tarefa
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"title":"Estudar Laravel","description":"Capítulo 5"}'

# Buscar tarefa
curl http://localhost:8000/api/tasks/1

# Atualizar tarefa
curl -X PUT http://localhost:8000/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{"status":1}'

# Deletar tarefa
curl -X DELETE http://localhost:8000/api/tasks/1
```

---

## 📁 Estrutura do Projeto

```
api/
├── app/
│   ├── Exceptions/              # Exceções customizadas
│   │   ├── TaskNotFoundException.php
│   │   ├── InvalidTaskDataException.php
│   │   ├── TaskOperationException.php
│   │   └── UnauthorizedTaskAccessException.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── TaskController.php    # Controller da API
│   │   └── Resources/
│   │       └── TaskResource.php      # Formatação de resposta
│   │
│   ├── Models/
│   │   └── Task.php                  # Model Eloquent
│   │
│   ├── Repositories/
│   │   └── TaskRepository.php        # Camada de dados
│   │
│   └── Services/
│       └── TaskService.php           # Lógica de negócio
│
├── database/
│   └── migrations/
│       └── 2025_10_14_143448_create_tasks_table.php
│
├── docker/                      # Configurações Docker
│   ├── nginx/
│   │   └── conf.d/
│   │       └── default.conf     # Config Nginx
│   └── php/
│       └── local.ini            # Config PHP
│
├── routes/
│   └── api.php                  # Rotas da API
│
├── .env                         # Variáveis de ambiente
├── docker-compose.yml           # Orquestração Docker
├── Dockerfile                   # Imagem PHP
├── Makefile                     # Comandos automatizados
├── docker-setup.sh              # Script de setup
├── DOCKER.md                    # Doc completa do Docker
└── INSTALACAO.md                # Guia de instalação
```

### Descrição dos Componentes

#### Controllers
- **TaskController**: Gerencia requisições HTTP e retorna respostas JSON

#### Services
- **TaskService**: Contém validações e lógica de negócio

#### Repositories
- **TaskRepository**: Abstrai acesso ao banco de dados

#### Models
- **Task**: Representa a tabela `tasks` no banco

#### Resources
- **TaskResource**: Formata a resposta da API de forma consistente

#### Exceptions
- **TaskNotFoundException**: Quando uma tarefa não existe (404)
- **InvalidTaskDataException**: Quando dados são inválidos (422)
- **TaskOperationException**: Erros em operações (500)
- **UnauthorizedTaskAccessException**: Sem permissão (403)

---

## 🧪 Testes

### Executar Testes

```bash
docker-compose exec app php artisan test

---

## 🗄️ Banco de Dados

### Estrutura da Tabela `tasks`

| Campo       | Tipo         | Descrição                |
|-------------|--------------|--------------------------|
| id          | BIGINT       | Chave primária (auto)    |
| title       | VARCHAR(255) | Título da tarefa         |
| description | TEXT         | Descrição detalhada      |
| status      | TINYINT      | Status (0=pendente, 1=concluído) |
| created_at  | TIMESTAMP    | Data de criação          |
| updated_at  | TIMESTAMP    | Data de atualização      |

