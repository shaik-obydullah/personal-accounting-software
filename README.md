# Personal Accounting System

Self-hosted, API-first personal finance web app. Record incomes and expenses against wallets, with an automatic cashbook, audit trail, settings, and an analytics dashboard.

## Tech Stack

![PHP](https://img.shields.io/badge/PHP-8.5-8892BF?style=for-the-badge&logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-8.1-000000?style=for-the-badge&logo=symfony&logoColor=white)
![Doctrine](https://img.shields.io/badge/Doctrine%20ORM-3.6-000000?style=for-the-badge&logo=doctrine&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-10.11-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![Nginx](https://img.shields.io/badge/Nginx-1.x-009639?style=for-the-badge&logo=nginx&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.4-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-4.4-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)

## Features

- **Dashboard** — total income / expense / balance + monthly and per-wallet charts (Chart.js)
- **Wallets** — income & expense categories
- **Incomes & Expenses** — full CRUD, each transaction auto-writes a cashbook entry
- **Cashbook** — automatic ledger with descriptions resolved from references
- **Activities** — audit trail (login, actions, IP)
- **Settings** — editable key/value configuration incl. selectable currencies
- Search (debounced) + pagination on every list

## Quick Start

```bash
docker compose up -d --build
```

| Service    | URL                   |
| ---------- | --------------------- |
| App        | http://localhost:8040 |
| phpMyAdmin | http://localhost:8041 |

Default login: `admin@accounting.com` / `password`

## Screenshots

### Login

![Login](screenshots/Login.png)

### Dashboard

![Dashboard](screenshots/Dashboard.png)

### Wallets

![Wallets](screenshots/Wallets.png)

### Incomes

![Incomes](screenshots/Incomes.png)

### Expenses

![Expenses](screenshots/Expenses.png)

### Cashbook

![Cashbook](screenshots/Cashbook.png)

### Activities

![Activities](screenshots/Activities.png)

### Settings

![Settings](screenshots/Settings.png)

## License

Proprietary.
