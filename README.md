# 💰 Personal Finance API

A RESTful API for personal finance management, built with **Laravel 12**, **PostgreSQL 15**, and containerized with **Docker**. Features complete authentication, transaction and category management, monthly summaries, API documentation with Swagger, and observability with Prometheus and Grafana.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Getting Started](#getting-started)
- [Environment Variables](#environment-variables)
- [API Endpoints](#api-endpoints)
- [Authentication](#authentication)
- [Business Rules](#business-rules)
- [Project Structure](#project-structure)
- [API Documentation](#api-documentation)
- [Observability](#observability)
- [Contributing](#contributing)

---

## Overview

The Personal Finance API allows users to manage their personal finances through a secure, authenticated REST API. Each user has isolated access to their own data — categories and transactions are always scoped to the authenticated user.

**Key capabilities:**
- User registration and authentication via Bearer Token (Laravel Sanctum)
- Full CRUD for income and expense categories
- Full CRUD for financial transactions with filtering by month and category
- Monthly financial summary (total income, total expenses, balance)
- Interactive API documentation via Swagger UI
- Real-time metrics via Prometheus + Grafana dashboard

---

## Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.4 |
| Framework | Laravel 12 |
| Database | PostgreSQL 15 |
| Authentication | Laravel Sanctum (Bearer Token) |
| API Documentation | L5-Swagger (OpenAPI 3.0) |
| Metrics | Spatie Laravel Prometheus |
| Observability | Prometheus + Grafana |
| Containerization | Docker + Docker Compose |

---

## Architecture

The project follows a layered architecture with clear separation of concerns:

```
HTTP Request
     ↓
Form Request        → Input validation (422 on failure)
     ↓
Controller          → Orchestrates the request flow
     ↓
Mapper              → Transforms data between layers
     ↓
Repository          → Database operations (via Interface)
     ↓
Model               → Eloquent ORM representation
     ↓
JSON Response
```

**Design patterns used:**
- **Repository Pattern** — abstracts database operations behind interfaces, making the codebase testable and swappable
- **DTO (Data Transfer Object)** — typed objects that carry data between layers without exposing database models
- **Mapper** — handles all transformations between Request arrays, DTOs, and Models
- **Dependency Injection** — Laravel's service container binds interfaces to implementations via `AppServiceProvider`

---

## Getting Started

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running
- Git

### Installation

**1. Clone the repository**
```bash
git clone https://github.com/Cesararanha/personal-finance-api.git
cd personal-finance-api
```

**2. Copy the environment file**
```bash
cp .env.example .env
```

**3. Start the containers**
```bash
docker compose up -d
```

**4. Run database migrations**
```bash
docker compose exec app php artisan migrate
```

**5. Generate the application key** *(if not already set)*
```bash
docker compose exec app php artisan key:generate
```

The API will be available at `http://localhost:8000`.

### Useful Commands

```bash
# Stop containers
docker compose down

# View application logs
docker compose logs app

# Run code formatter (Laravel Pint)
docker compose exec app ./vendor/bin/pint

# Regenerate Swagger documentation
docker compose exec app php artisan l5-swagger:generate

# Access PostgreSQL shell
docker compose exec db psql -U finance_user -d finance_db
```

---

## Environment Variables

| Variable | Description | Default |
|---|---|---|
| `APP_NAME` | Application name | `Laravel` |
| `APP_ENV` | Environment (`local`, `production`) | `local` |
| `APP_KEY` | Application encryption key | — |
| `APP_DEBUG` | Enable debug mode | `true` |
| `APP_URL` | Application base URL | `http://localhost` |
| `DB_CONNECTION` | Database driver | `pgsql` |
| `DB_HOST` | Database host | `db` |
| `DB_PORT` | Database port | `5432` |
| `DB_DATABASE` | Database name | `finance_db` |
| `DB_USERNAME` | Database user | `finance_user` |
| `DB_PASSWORD` | Database password | `finance_pass` |

---

## API Endpoints

### Public Routes

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/register` | Register a new user |
| `POST` | `/api/login` | Authenticate and receive a token |

### Protected Routes *(require Bearer Token)*

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/logout` | Revoke the current token |
| `GET` | `/api/me` | Get the authenticated user's data |
| `GET` | `/api/categories` | List all categories |
| `POST` | `/api/categories` | Create a category |
| `GET` | `/api/categories/{id}` | Get a category by ID |
| `PUT` | `/api/categories/{id}` | Update a category |
| `DELETE` | `/api/categories/{id}` | Delete a category |
| `GET` | `/api/transactions` | List all transactions |
| `GET` | `/api/transactions?month=2025-01` | Filter transactions by month |
| `GET` | `/api/transactions?category_id=1` | Filter transactions by category |
| `POST` | `/api/transactions` | Create a transaction |
| `GET` | `/api/transactions/{id}` | Get a transaction by ID |
| `PUT` | `/api/transactions/{id}` | Update a transaction |
| `DELETE` | `/api/transactions/{id}` | Delete a transaction |
| `GET` | `/api/summary?month=2025-01` | Get monthly financial summary |

---

## Authentication

This API uses **Bearer Token** authentication via Laravel Sanctum.

**1. Register or login to receive a token:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "user@email.com", "password": "password123"}'
```

**2. Include the token in subsequent requests:**
```bash
curl http://localhost:8000/api/categories \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**3. Logout to revoke the token:**
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Business Rules

- Every user can only access their own categories and transactions — all data is scoped by `user_id`
- Transaction `amount` is always a positive number — the `type` field (`income` or `expense`) determines the direction
- Account balance can be negative
- A category **cannot be deleted** if it has transactions linked to it — returns `409 Conflict`
- Passwords are never returned in any API response
- Login returns a token alongside the user data

### Response Format

**Success with body (200, 201):**
```json
{ "data": { ... } }
```

**Success without body (204):**
No response body.

**Client error (404, 409):**
```json
{ "message": "Category not found." }
```

**Validation error (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

**Server error (500):**
```json
{ "message": "Internal server error." }
```

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/         # Request orchestration
│   └── Requests/            # Input validation (Form Requests)
├── Models/                  # Eloquent models
├── DTOs/                    # Data Transfer Objects
├── Mappers/                 # Data transformation between layers
├── Repositories/
│   ├── Interfaces/          # Repository contracts
│   └── Implementations/     # Repository implementations
├── Providers/
│   ├── AppServiceProvider   # Interface → Implementation bindings
│   └── PrometheusServiceProvider  # Metrics configuration
└── Swagger/                 # OpenAPI annotations (separated from controllers)

docker/
├── prometheus.yml           # Prometheus scrape configuration
└── grafana/
    ├── datasources/         # Grafana data source (Prometheus)
    └── dashboards/          # Grafana dashboard provisioning
```

---

## API Documentation

The interactive API documentation is available via Swagger UI at:

```
http://localhost:8000/api/documentation
```

To regenerate the documentation after changes:
```bash
docker compose exec app php artisan l5-swagger:generate
```

The documentation includes all endpoints grouped by domain (Auth, Categories, Transactions, Summary), with request body schemas, parameter descriptions, and expected response codes.

---

## Observability

### Metrics Endpoint

Application metrics are exposed at:
```
http://localhost:8000/metrics
```

Current metrics:
- `app_laravel_users_total` — total registered users
- `app_laravel_categories_total` — total categories
- `app_laravel_transactions_total` — total transactions

### Prometheus

Access the Prometheus query interface at:
```
http://localhost:9090
```

Metrics are scraped from the application every **15 seconds**.

### Grafana

Access the Grafana dashboard at:
```
http://localhost:3000
```

Default credentials: `admin` / `admin`

The **Personal Finance API** dashboard is automatically provisioned under the **Laravel** folder and displays real-time counts for users, categories, and transactions.

---

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feat/your-feature`)
3. Commit your changes following the convention below
4. Push and open a Pull Request

### Commit Convention

| Prefix | Usage |
|---|---|
| `feat:` | New feature |
| `fix:` | Bug fix |
| `chore:` | Configuration, setup |
| `docs:` | Documentation |
| `refactor:` | Code refactoring without behavior change |

---

## Containers

| Container | Service | Port |
|---|---|---|
| `finance_app` | Laravel Application | `8000` |
| `finance_db` | PostgreSQL 15 | `5432` |
| `finance_prometheus` | Prometheus | `9090` |
| `finance_grafana` | Grafana | `3000` |

---

<p align="center">Built by <a href="https://github.com/your-username">Cesar Aranha</a></p>
