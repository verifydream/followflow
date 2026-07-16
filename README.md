# 🔗 FollowFlow

> Tidak ada lagi calon pelanggan yang terlupakan.

Sales follow-up automation untuk UMKM — monitor leads, kirim reminder otomatis via WhatsApp.

## Fitur

- Tambah lead via API / web form
- Auto-detect lead yang sudah stale (>5 hari tanpa kontak)
- Reminder otomatis via WhatsApp (Fonnte)
- Rekap lead: baru, aktif, stale, closed
- Cron job harian (jam 9 pagi)

## Tech Stack

- Node.js + Express
- PostgreSQL
- Fonnte API
- node-cron

## Quick Start

```bash
cp .env.example .env
npm install
npm run db:migrate
npm run dev
```

## API

- `GET /api/leads?phone=08xxx` — List leads
- `POST /api/leads` — Add lead `{ phone, name, lead_phone, email, company, value, notes }`
- `DELETE /api/leads/:id` — Remove lead
- `POST /api/leads/:id/contacted` — Mark as contacted

## License

MIT
