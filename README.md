# 🔗 FollowFlow

> Tidak ada lagi calon pelanggan yang terlupakan.

Sales follow-up automation untuk UMKM — monitor leads, kirim reminder otomatis via WhatsApp.

## 🚀 Tech Stack

| Layer | Tech |
|-------|------|
| Frontend | React (Vite) |
| Backend | Laravel 13 + Sanctum |
| Database | PostgreSQL |
| WhatsApp | Fonnte API |

## 📦 Features

- ✅ Lead CRUD (name, phone, email, company, value)
- ✅ Auto stale detection (>5 hari tanpa kontak)
- ✅ Daily cron job (jam 9 pagi) — reminder via WhatsApp
- ✅ Dashboard: total, new, stale, value stats
- ✅ Mark as contacted (reset timer)

## 🏁 Quick Start

```bash
# Backend
cp .env.example .env
composer install
php artisan migrate
php artisan serve

# Frontend
npm install && npm run build
```

## 🔧 Environment Variables

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=followflow
DB_USERNAME=your_user
DB_PASSWORD=your_pass

FONNTE_TOKEN=your_token
FONNTE_BASE_URL=https://api.fonnte.com
```

## 📡 API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/register` | ❌ | Register new user |
| POST | `/api/login` | ❌ | Login |
| GET | `/api/leads` | ✅ | List all leads |
| POST | `/api/leads` | ✅ | Create lead |
| DELETE | `/api/leads/{id}` | ✅ | Delete lead |
| POST | `/api/leads/{id}/contacted` | ✅ | Mark as contacted |
| GET | `/api/dashboard` | ✅ | Dashboard stats |

## 📂 Project Structure

```
followflow/
├── app/
│   ├── Http/Controllers/
│   │   ├── LeadController.php
│   │   └── AuthController.php
│   └── Models/
│       ├── User.php
│       ├── Lead.php
│       └── FollowUp.php
├── database/migrations/
├── resources/js/react/
│   ├── App.jsx
│   └── main.jsx
├── routes/
│   ├── api.php
│   └── web.php
└── .env.example
```

## 📄 License

MIT
