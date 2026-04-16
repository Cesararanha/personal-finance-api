# 💰 Personal Finance API

A RESTful API for personal finance management, built with **Laravel 12**, **PostgreSQL 15**, and containerized with **Docker**. Features complete authentication, expense tracking, monthly income management, savings goals ("caixinhas"), recurring transactions, async PDF/CSV report generation via **RabbitMQ**, email notifications via **Mailpit**, API documentation with Swagger, and observability with Prometheus and Grafana.

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
- [Roadmap](#roadmap)
- [Contributing](#contributing)

---

## Overview

The Personal Finance API allows users to manage their personal finances through a secure, authenticated REST API. Each user has fully isolated access to their own data — all resources are always scoped to the authenticated user.

**Key capabilities:**
- User registration and authentication via Bearer Token (Laravel Sanctum)
- Full CRUD for expense categories with archiving support
- Full CRUD for financial transactions (expenses only) with advanced filtering and sorting
- Monthly income management as a separate entity (`/api/incomes`)
- Savings goals ("caixinhas") with deposit, withdraw and transaction history
- Profile management — update name, phone and password
- Monthly financial summary with balance, savings balance and breakdown by category
- Recurring transactions with automatic daily processing via scheduled job
- Async PDF/CSV report generation dispatched to RabbitMQ — email sent when ready
- Email notifications on transaction creation via Mailpit (dev) / SMTP (prod)
- Interactive API documentation via Swagger UI
- Real-time application metrics via Prometheus + Grafana dashboard

---

## Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.4 |
| Framework | Laravel 12 |
| Database | PostgreSQL 15 |
| Authentication | Laravel Sanctum (Bearer Token) |
| Message Broker | RabbitMQ 3.13 |
| Queue Driver | vladimir-yuldashev/laravel-queue-rabbitmq |
| PDF Generation | barryvdh/laravel-dompdf |
| Email (dev) | Mailpit |
| API Documentation | L5-Swagger (OpenAPI 3.0) |
| API Testing | Newman (Postman CLI) |
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

**Async processing flow (RabbitMQ):**
```
API (finance_app)                RabbitMQ            Worker (finance_worker)
      │                              │                        │
      │── dispatch Job ─────────────►│                        │
      │                              │── consume Job ────────►│
      │                              │                        │── generate PDF/CSV
      │                              │                        │── send email (Mailpit)
      │                              │                        │── update status → done
```
- `finance_worker` — consumes queues `notifications` and `reports` via `php artisan queue:work`
- `finance_scheduler` — runs `php artisan schedule:work` to dispatch recurring transaction jobs daily at 00:00

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

# View worker logs
docker compose logs worker

# Run API regression tests with Newman
npm run test:api

# Force process recurring transactions manually
docker compose exec app php artisan schedule:run
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
| `RABBITMQ_HOST` | RabbitMQ host | `rabbitmq` |
| `RABBITMQ_PORT` | RabbitMQ AMQP port | `5672` |
| `RABBITMQ_USER` | RabbitMQ user | `guest` |
| `RABBITMQ_PASSWORD` | RabbitMQ password | `guest` |
| `RABBITMQ_QUEUE` | Default queue name | `default` |
| `MAIL_MAILER` | Mail driver | `smtp` |
| `MAIL_HOST` | SMTP host | `mailpit` |
| `MAIL_PORT` | SMTP port | `1025` |
| `MAIL_FROM_ADDRESS` | Sender address | `noreply@finance.local` |

---

## API Endpoints

### Public Routes

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/register` | Register a new user |
| `POST` | `/api/login` | Authenticate and receive a token |

### Protected Routes *(require Bearer Token)*

#### Auth

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/logout` | Revoke the current token |
| `GET` | `/api/me` | Get the authenticated user's profile |
| `PUT` | `/api/me` | Update name, phone or password |

#### Categories

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/categories` | List categories (active only by default) |
| `POST` | `/api/categories` | Create a category |
| `GET` | `/api/categories/{id}` | Get a category by ID |
| `PUT` | `/api/categories/{id}` | Update a category |
| `PATCH` | `/api/categories/{id}/archive` | Archive a category |
| `DELETE` | `/api/categories/{id}` | Delete a category (only if no transactions) |

#### Transactions

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/transactions` | List transactions with filters |
| `POST` | `/api/transactions` | Create a transaction (expense only) |
| `GET` | `/api/transactions/{id}` | Get a transaction by ID |
| `PUT` | `/api/transactions/{id}` | Update a transaction (supports partial update) |
| `DELETE` | `/api/transactions/{id}` | Delete a transaction |

#### Incomes

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/incomes` | List monthly incomes |
| `POST` | `/api/incomes` | Register a monthly income |
| `DELETE` | `/api/incomes/{id}` | Delete a monthly income |

#### Savings

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/savings` | List all savings goals |
| `POST` | `/api/savings` | Create a savings goal |
| `GET` | `/api/savings/{id}` | Get a savings goal by ID |
| `PUT` | `/api/savings/{id}` | Update a savings goal |
| `DELETE` | `/api/savings/{id}` | Delete a savings goal (only if balance is zero) |
| `POST` | `/api/savings/{id}/deposit` | Deposit into a savings goal |
| `POST` | `/api/savings/{id}/withdraw` | Withdraw from a savings goal |
| `GET` | `/api/savings/{id}/history` | Get transaction history of a savings goal |

#### Recurring Transactions

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/recurring-transactions` | List all recurring transactions |
| `POST` | `/api/recurring-transactions` | Create a recurring transaction |
| `GET` | `/api/recurring-transactions/{id}` | Get a recurring transaction by ID |
| `PUT` | `/api/recurring-transactions/{id}` | Update a recurring transaction |
| `DELETE` | `/api/recurring-transactions/{id}` | Delete a recurring transaction |

#### Reports

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/reports` | Request async PDF or CSV report generation |
| `GET` | `/api/reports/{id}` | Check report status |
| `GET` | `/api/reports/{id}/download` | Download the generated file (requires auth) |

> The signed download link sent by email (`/api/reports/{id}/file`) does not require authentication — it expires in 24 hours.

#### Summary

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/summary?month=2025-01` | Get monthly financial summary |

---

### Filters available on `GET /api/transactions`

| Query Param | Example | Description |
|---|---|---|
| `month` | `?month=2025-01` | Filter by month (YYYY-MM) |
| `category_id` | `?category_id=2` | Filter by category |
| `start_date` | `?start_date=2025-01-01` | Filter from date (YYYY-MM-DD) |
| `end_date` | `?end_date=2025-01-31` | Filter until date (YYYY-MM-DD) |
| `min_amount` | `?min_amount=100` | Minimum transaction amount |
| `max_amount` | `?max_amount=500` | Maximum transaction amount |
| `sort_by` | `?sort_by=amount` | Sort field (`date`, `amount`, `description`) |
| `order` | `?order=asc` | Sort direction (`asc`, `desc`) |

Filters are combinable: `?month=2025-01&min_amount=100&sort_by=amount&order=asc`

### Filters available on `GET /api/categories`

| Query Param | Example | Description |
|---|---|---|
| `archived` | `?archived=true` | Include archived categories in the response |

### Filters available on `GET /api/incomes`

| Query Param | Example | Description |
|---|---|---|
| `month` | `?month=2025-01` | Filter incomes by month (YYYY-MM) |

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

### General
- Every user can only access their own data — all resources are scoped by `user_id`
- Sensitive data (CPF, phone, birth date) is never returned in any API response
- Passwords are never returned in any API response
- All user-facing messages are in Brazilian Portuguese

### Transactions
- Transactions are **expenses only** — income is managed separately via `/api/incomes`
- `amount` is always stored as a positive number
- A transaction **cannot be created** if the linked category is archived — returns `422`
- `PUT /api/transactions/{id}` supports partial updates — only the fields sent will be updated

### Categories
- A category **cannot be deleted** if it has transactions linked to it — returns `409 Conflict`
- Archiving (`PATCH /archive`) sets `is_active = false` and hides the category from default listings
- Archived categories are still visible with `?archived=true` and remain linked to historical transactions

### Incomes
- Incomes are standalone monthly records, not linked to transactions
- The `received_at` date determines which month the income belongs to for summary calculations

### Savings
- A savings goal **cannot be deleted** if its balance is greater than zero — returns `409 Conflict`
- A withdraw that exceeds the current balance returns `422 Unprocessable Entity` with the available balance in the message
- Every deposit and withdraw is recorded as a `SavingTransaction` and accessible via `/history`
- Deposit and withdraw operations are wrapped in database transactions to guarantee consistency

### Recurring Transactions
- Every active recurring transaction with `next_due_date <= today` is processed daily at 00:00 by `ProcessRecurringTransactionsJob`
- After processing, a real `Transaction` is created and `next_due_date` is advanced according to the frequency
- Deactivating a recurring transaction (`is_active = false`) stops it from being processed
- An email notification is sent for each transaction created by a recurring job

### Reports
- Report generation is asynchronous — the API responds `202` immediately and the worker processes the job
- Status transitions: `pending` → `processing` → `done` | `failed`
- Supported formats: `pdf` and `csv`
- Available filters: `month` (YYYY-MM), `start_date` + `end_date`, `category_id`
- When done, an email is sent with a signed download link valid for **24 hours**
- The `/api/reports/{id}/download` endpoint requires Bearer auth and has no expiry

### Summary
- The `month` parameter is required — returns `422` if missing
- `total_income` — sum of all `MonthlyIncome` records for the given month
- `total_expenses` — sum of all `Transaction` records for the given month
- `balance` = `total_income` - `total_expenses`
- `savings_balance` — sum of all savings goal balances (across all time, not month-specific)
- `available_balance` = `balance` - `savings_balance`
- `by_category` — groups expenses by category with total, percentage and transaction count

### Response Format

**Success with body (200, 201):**
```json
{ "data": { ... } }
```

**Success without body (204):**
No response body.

**Client error (404, 409, 422):**
```json
{ "message": "Descrição do erro em português." }
```

**Validation error (422):**
```json
{
  "message": "The name field is required.",
  "errors": {
    "name": ["The name field is required."]
  }
}
```

**Server error (500):**
```json
{ "message": "Ocorreu um erro interno. Tente novamente." }
```

> ⚠️ The API never returns status 200 with an error body.

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── CategoryController.php
│   │   ├── TransactionController.php
│   │   ├── IncomeController.php
│   │   ├── SavingController.php
│   │   ├── RecurringTransactionController.php
│   │   ├── ReportController.php
│   │   └── SummaryController.php
│   └── Requests/
│       ├── StoreCategoryRequest.php
│       ├── UpdateCategoryRequest.php
│       ├── StoreTransactionRequest.php
│       ├── UpdateTransactionRequest.php
│       ├── StoreIncomeRequest.php
│       ├── StoreSavingRequest.php
│       ├── UpdateSavingRequest.php
│       ├── SavingMovementRequest.php
│       └── StoreReportRequest.php
├── Jobs/
│   ├── SendTransactionNotificationJob.php
│   ├── ProcessRecurringTransactionsJob.php
│   └── GenerateReportJob.php
├── Mail/
│   ├── TransactionCreatedMail.php
│   └── ReportReadyMail.php
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Transaction.php
│   ├── MonthlyIncome.php
│   ├── Saving.php
│   ├── SavingTransaction.php
│   ├── RecurringTransaction.php
│   └── ReportRequest.php
├── DTOs/
│   ├── CategoryDTO.php
│   ├── TransactionDTO.php
│   ├── MonthlyIncomeDTO.php
│   ├── SavingDTO.php
│   ├── SavingTransactionDTO.php
│   └── RecurringTransactionDTO.php
├── Mappers/
│   ├── CategoryMapper.php
│   ├── TransactionMapper.php
│   ├── MonthlyIncomeMapper.php
│   ├── SavingMapper.php
│   ├── SavingTransactionMapper.php
│   └── RecurringTransactionMapper.php
├── Repositories/
│   ├── Interfaces/
│   │   ├── CategoryRepositoryInterface.php
│   │   ├── TransactionRepositoryInterface.php
│   │   ├── MonthlyIncomeRepositoryInterface.php
│   │   ├── SavingRepositoryInterface.php
│   │   ├── UserRepositoryInterface.php
│   │   └── RecurringTransactionRepositoryInterface.php
│   ├── CategoryRepository.php
│   ├── TransactionRepository.php
│   ├── MonthlyIncomeRepository.php
│   ├── SavingRepository.php
│   ├── UserRepository.php
│   └── RecurringTransactionRepository.php
├── Providers/
│   ├── AppServiceProvider.php
│   └── PrometheusServiceProvider.php
└── Swagger/
    ├── AuthSwagger.php
    ├── CategorySwagger.php
    ├── TransactionSwagger.php
    ├── IncomeSwagger.php
    ├── SavingSwagger.php
    ├── RecurringTransactionSwagger.php
    ├── ReportSwagger.php
    └── SummarySwagger.php

database/
└── migrations/
    ├── create_users_table.php
    ├── create_categories_table.php
    ├── create_transactions_table.php
    ├── add_is_active_to_categories_table.php
    ├── create_monthly_incomes_table.php
    ├── create_savings_table.php
    ├── create_savings_transactions_table.php
    ├── create_recurring_transactions_table.php
    └── create_report_requests_table.php

docker/
├── prometheus.yml
└── grafana/
    ├── datasources/
    │   └── prometheus.yml
    └── dashboards/
        └── personal-finance.json

routes/
└── api.php
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

The documentation covers all endpoints grouped by domain (**Auth**, **Categories**, **Transactions**, **Incomes**, **Savings**, **Recurring Transactions**, **Reports**, **Summary**) with full request body schemas, parameter descriptions, and all expected response codes.

---

## Observability

### Metrics Endpoint

Application metrics are exposed at:
```
http://localhost:8000/metrics
```

**Available metrics:**

| Metric | Description |
|---|---|
| `app_laravel_users_total` | Total registered users |
| `app_laravel_categories_total` | Total categories |
| `app_laravel_categories_active_total` | Total active categories |
| `app_laravel_categories_archived_total` | Total archived categories |
| `app_laravel_transactions_total` | Total transactions |
| `app_laravel_transactions_avg_amount` | Average transaction amount (R$) |
| `app_laravel_incomes_total` | Total income records |
| `app_laravel_incomes_amount_total` | Sum of all income amounts (R$) |
| `app_laravel_savings_total` | Total savings goals |
| `app_laravel_savings_balance_total` | Total balance across all savings goals (R$) |
| `app_laravel_recurring_transactions_total` | Total recurring transactions |
| `app_laravel_recurring_transactions_active_total` | Total active recurring transactions |
| `app_laravel_report_requests_total` | Total report requests |
| `app_laravel_report_requests_done_total` | Total completed reports |

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

The **Personal Finance API** dashboard is automatically provisioned and displays:
- **Row 1** — Total users, transactions, incomes and savings goals
- **Row 2** — Active categories, archived categories, total categories and average transaction amount
- **Row 3** — Total income sum, total savings balance, categories donut chart and platform summary table
- **Row 4** — Total recurring transactions, active recurring transactions, total reports requested and completed reports

Also accessible at `http://localhost:15672` — **RabbitMQ Management UI** (default credentials: `guest` / `guest`) to inspect queues and message rates.

**Email (dev):** All outgoing emails are captured by Mailpit at `http://localhost:8025`.

---

## Roadmap

The following features are planned for the next version:

- **Soft delete** — all entities will support soft deletion for data recovery and audit history
- **Transaction tags** — free-form tags for finer classification beyond categories
- **Budget goals** — set monthly spending limits per category with alerts when approaching the limit
- **Multi-currency support** — track transactions in different currencies with conversion

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
| `finance_rabbitmq` | RabbitMQ + Management UI | `5672` / `15672` |
| `finance_mailpit` | Mailpit SMTP + Web UI | `1025` / `8025` |
| `finance_worker` | Laravel Queue Worker | — |
| `finance_scheduler` | Laravel Scheduler | — |
| `finance_prometheus` | Prometheus | `9090` |
| `finance_grafana` | Grafana | `3000` |

---

<p align="center">Built by <a href="https://github.com/Cesararanha">Cesar Aranha</a></p>
