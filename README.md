# 🔗 FollowFlow

> Tidak ada lagi calon pelanggan yang terlupakan.

Sales follow-up automation untuk UMKM — monitor leads, kirim reminder otomatis via WhatsApp.

## 🚀 Tech Stack

FollowFlow uses a robust stack to ensure reliability and speed:
- **Backend:** Laravel
- **Frontend:** Vite/React
- **Database:** PostgreSQL
- **WhatsApp Integration:** Fonnte

## 📦 Features

- ✅ Lead CRUD (name, phone, email, company, value)
- ✅ Auto stale detection (>5 hari tanpa kontak)
- ✅ Daily cron job (jam 9 pagi) — reminder via WhatsApp
- ✅ Dashboard: total, new, stale, value stats
- ✅ Mark as contacted (reset timer)


## 📖 What is FollowFlow?

FollowFlow is an auto follow-up reminder tool for sales leads via WhatsApp. It helps small and medium enterprises (UMKM) track potential customers and ensure no lead is forgotten by automating WhatsApp reminders.

## 🔄 Lead Lifecycle

The lifecycle of a lead in FollowFlow follows this progression:
1. **New**: A lead is added to the system.
2. **Contacted**: The sales team reaches out to the lead.
3. **Stale**: If there is no contact for >5 days, the lead is marked as stale.
4. **Reminded**: The system sends an auto-reminder via WhatsApp.
5. **Contacted (reset timer)**: The sales team contacts the lead again, resetting the stale timer.

## ⏱️ Cron Job Documentation

FollowFlow relies on a scheduled cron job to perform automated tasks.

- **What Runs**: The cron job checks for stale leads (>5 days without contact) and dispatches automated WhatsApp reminders using the Fonnte API.
- **Schedule**: The job runs **daily at 9:00 AM**.
- **Manual Test Command**: To manually trigger the schedule for testing purposes, you can run:
  ```bash
  php artisan schedule:run
  ```
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

## 📡 API Endpoints & Dashboard Stats

### API Endpoints
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/register` | ❌ | Register new user |
| POST | `/api/login` | ❌ | Login |
| GET | `/api/leads` | ✅ | List all leads |
| POST | `/api/leads` | ✅ | Create lead |
| GET | `/api/leads/{id}` | ✅ | View specific lead details and follow-ups |
| DELETE | `/api/leads/{id}` | ✅ | Delete lead |
| POST | `/api/leads/{id}/contacted` | ✅ | Mark as contacted (resets stale timer) |
| GET | `/api/dashboard` | ✅ | Fetch dashboard statistics |

### Dashboard Stats
The `/api/dashboard` endpoint provides a summary of lead metrics:
- **Total**: Total number of leads assigned to the user.
- **New**: Number of leads with the status 'new'.
- **Stale**: Number of leads that have not been contacted in >5 days (excluding closed/won leads).
- **Total Value**: The sum of the `value` field across all leads for the user.

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
