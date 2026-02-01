# ServiceHub

ServiceHub é uma aplicação web para **gestão de ordens de serviço**, desenvolvida como teste técnico, utilizando **Laravel 12**, **Inertia.js** e **Vue 3**. O foco foi entregar um fluxo completo com autenticação, relacionamentos, upload de anexo, processamento assíncrono e testes automatizados.

---

## Requisitos atendidos

- Autenticação padrão Laravel (Breeze + Inertia)
- Relacionamentos **1:1** e **1:N** implementados e **testados**
- Criação de Tickets via UI (Inertia + Vue)
- Upload opcional de anexo (TXT/JSON)
- Processamento assíncrono em fila (Database Queue) para enriquecer `TicketDetail`
- Notificação (Database Notifications) criada após o processamento
- Docker para desenvolvimento local

---

## Tecnologias

- **Backend:** Laravel 12 (PHP 8.3)
- **Frontend:** Inertia.js + Vue 3 + Vite
- **Banco:** MySQL
- **Fila:** Database Queue
- **Notificações:** Database Notifications
- **Testes:** PHPUnit
- **Docker:** PHP-FPM + Nginx + MySQL

---

## Modelagem do Domínio

- **Company** → possui muitos **Projects** (1:N)
- **Project** → pertence a **Company** e possui muitos **Tickets** (1:N)
- **Ticket** → pertence a **Project**, pertence a **User** e possui **TicketDetail** (1:1)
- **TicketDetail** → detalhe técnico enriquecido a partir do anexo
- **User** → possui **UserProfile** (1:1) e pertence a uma **Company**
- **UserProfile** → dados adicionais (telefone, cargo)

---

## Fluxo

1. Usuário faz **cadastro**
   - Cria automaticamente `Company` e `UserProfile`.
2. Usuário cria **Projects** da sua empresa.
3. Usuário cria **Tickets** em um Project da própria empresa.
   - Pode anexar **TXT** ou **JSON**.
4. Um **Job** em fila processa o anexo e enriquece o `TicketDetail`.
5. Uma **Notification** é criada no banco após o processamento.
6. Dashboard mostra estatísticas e notificações.

---

## Testes Unitarios

```bash
php artisan test
```

> Todos os testes estão passando

---

# Docker (modo principal)

> Pré-requisitos: Docker Desktop instalado e em execução.

A aplicação roda no Docker em **http://localhost:8080**

## 1) Subir os containers

```bash
docker compose up -d --build
```

## 2) Instalar dependências e configurar o Laravel

```bash
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan storage:link
```

## 3) Ajuste recomendado do `.env` (dentro do container)

Abra o `.env` e confirme:

```env
APP_URL=http://localhost:8080
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=servicehub
DB_USERNAME=servicehub
DB_PASSWORD=servicehub
QUEUE_CONNECTION=database
```

Se você alterar `.env` após subir, rode:

```bash
docker compose exec app php artisan config:clear
```

## 4) Rodar a fila (Job)

Em outro terminal:

```bash
docker compose exec app php artisan queue:work
```

> Esse comando precisa estar rodando para o processamento do anexo e criação da Notification acontecerem.

## 5) Front-end (Vite)

A forma mais simples é rodar o Vite **fora do Docker**:

```bash
npm install
npm run dev
```

- App: **http://localhost:8080**
- Vite: **http://localhost:5173**

> Se você usar adblock/extensões que bloqueiam scripts, pode precisar desativar para evitar erros tipo `ERR_BLOCKED_BY_CLIENT`.

## 6) Rodar testes dentro do Docker (opcional)

```bash
docker compose exec app php artisan test
```

---

## Setup local sem uso de Docker

Requisitos: PHP 8.3, Composer, Node.js, MySQL.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run dev
php artisan serve
php artisan queue:work
```

---

## Uploads e Preview

- Uploads ficam em `storage/app/public`
- Link público criado com:

```bash
php artisan storage:link
```

- Preview na UI suporta:
  - imagens (png/jpg/webp/gif)
  - txt
  - json (formatado no modal)

---

## Autor do projeto - teste tecnico para KPMG

Carlos Guilherme Fontes Pereira
