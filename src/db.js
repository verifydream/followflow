import pg from 'pg';
import dotenv from 'dotenv';
dotenv.config();

const pool = new pg.Pool({ connectionString: process.env.DATABASE_URL });
export default pool;

export async function getOrCreateUser(phone, name) {
  const { rows } = await pool.query(
    'INSERT INTO users (phone, name) VALUES ($1, $2) ON CONFLICT (phone) DO UPDATE SET name = $2 RETURNING *',
    [phone, name]
  );
  return rows[0];
}

export async function addLead(userId, { name, phone, email, company, value, notes }) {
  const { rows } = await pool.query(
    `INSERT INTO leads (user_id, name, phone, email, company, value, notes)
     VALUES ($1, $2, $3, $4, $5, $6, $7) RETURNING *`,
    [userId, name, phone || null, email || null, company || null, value || 0, notes || null]
  );
  return rows[0];
}

export async function getLeads(userId) {
  const { rows } = await pool.query(
    'SELECT * FROM leads WHERE user_id = $1 ORDER BY last_contact ASC',
    [userId]
  );
  return rows;
}

export async function updateLeadContact(leadId) {
  await pool.query('UPDATE leads SET last_contact = NOW() WHERE id = $1', [leadId]);
}

export async function updateLeadStatus(leadId, status) {
  await pool.query('UPDATE leads SET status = $1 WHERE id = $1', [status]);
}

export async function getStaleLeads(userId, days) {
  const { rows } = await pool.query(
    `SELECT * FROM leads WHERE user_id = $1
     AND last_contact < NOW() - INTERVAL '1 day' * $2
     AND status NOT IN ('closed', 'won')
     ORDER BY last_contact ASC`,
    [userId, days]
  );
  return rows;
}

export async function logFollowUp(leadId, method, message) {
  await pool.query(
    'INSERT INTO follow_ups (lead_id, method, message) VALUES ($1, $2, $3)',
    [leadId, method, message]
  );
}

export async function deleteLead(leadId) {
  await pool.query('DELETE FROM leads WHERE id = $1', [leadId]);
}

export async function getAllUsers() {
  const { rows } = await pool.query('SELECT * FROM users');
  return rows;
}
