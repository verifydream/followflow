import express from 'express';
import cron from 'node-cron';
import dotenv from 'dotenv';
import * as db from './db.js';
import { sendMessage } from './fonnte.js';

dotenv.config();

const app = express();
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

const FOLLOW_UP_DAYS = parseInt(process.env.FOLLOW_UP_DAYS) || 5;

// --- Health ---
app.get('/health', (_, res) => res.json({ status: 'ok', service: 'followflow' }));

// --- API: Leads ---
app.get('/api/leads', async (req, res) => {
  const phone = req.query.phone;
  if (!phone) return res.status(400).json({ error: 'phone required' });
  const user = await db.getOrCreateUser(phone, '');
  const leads = await db.getLeads(user.id);
  res.json(leads);
});

app.post('/api/leads', async (req, res) => {
  const { phone, name, lead_phone, email, company, value, notes } = req.body;
  if (!phone || !name) return res.status(400).json({ error: 'phone and name required' });
  const user = await db.getOrCreateUser(phone, '');
  const lead = await db.addLead(user.id, { name, phone: lead_phone, email, company, value, notes });
  res.json(lead);
});

app.delete('/api/leads/:id', async (req, res) => {
  await db.deleteLead(parseInt(req.params.id));
  res.json({ ok: true });
});

app.post('/api/leads/:id/contacted', async (req, res) => {
  await db.updateLeadContact(parseInt(req.params.id));
  res.json({ ok: true });
});

// --- Cron: Check stale leads ---
const CHECK_CRON = '0 9 * * *'; // Every day at 9 AM

cron.schedule(CHECK_CRON, async () => {
  console.log(`[CRON] Checking stale leads (>${FOLLOW_UP_DAYS} days)...`);
  try {
    const users = await db.getAllUsers();
    for (const user of users) {
      const stale = await db.getStaleLeads(user.id, FOLLOW_UP_DAYS);
      if (!stale.length) continue;

      const lines = [`⏰ *Follow-up Reminder*\n`];
      lines.push(`${stale.length} lead belum dihubungi ${FOLLOW_UP_DAYS}+ hari:\n`);

      for (const lead of stale) {
        const daysSince = Math.floor((Date.now() - new Date(lead.last_contact).getTime()) / 86400000);
        lines.push(`• *${lead.name}*${lead.company ? ` (${lead.company})` : ''} — ${daysSince} hari`);
        if (lead.notes) lines.push(`  Catatan: ${lead.notes}`);
      }

      lines.push(`\nKetik /rekap untuk lihat semua lead.`);

      await sendMessage(user.phone, lines.join('\n'));

      // Log follow-up for each
      for (const lead of stale) {
        await db.logFollowUp(lead.id, 'auto_reminder', `Auto reminder sent after ${FOLLOW_UP_DAYS}+ days`);
      }
    }
    console.log(`[CRON] Done. Checked ${users.length} users.`);
  } catch (err) {
    console.error('[CRON] Error:', err);
  }
});

// --- Start ---
const PORT = process.env.PORT || 3002;
app.listen(PORT, '0.0.0.0', () => {
  console.log(`🔗 FollowFlow running on port ${PORT}`);
  console.log(`   Cron: "${CHECK_CRON}" (stale leads >${FOLLOW_UP_DAYS} days)`);
});
