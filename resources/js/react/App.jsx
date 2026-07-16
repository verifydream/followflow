import { useState, useEffect } from 'react';

const API = '/api';

function App() {
  const [token, setToken] = useState(localStorage.getItem('token'));
  const [email, setEmail] = useState('');
  const [leads, setLeads] = useState([]);
  const [dash, setDash] = useState(null);
  const [newLead, setNewLead] = useState({ name: '', phone: '', email: '', company: '', value: 0 });

  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  async function loadLeads() {
    const r = await fetch(API + '/leads', { headers });
    setLeads(await r.json());
  }

  async function loadDash() {
    const r = await fetch(API + '/dashboard', { headers });
    setDash(await r.json());
  }

  async function login() {
    const r = await fetch(API + '/login', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ email }) });
    const d = await r.json();
    if (d.token) { localStorage.setItem('token', d.token); setToken(d.token); loadLeads(); loadDash(); }
  }

  async function addLead() {
    await fetch(API + '/leads', { method: 'POST', headers, body: JSON.stringify(newLead) });
    setNewLead({ name: '', phone: '', email: '', company: '', value: 0 });
    loadLeads(); loadDash();
  }

  async function markContacted(id) {
    await fetch(API + '/leads/' + id + '/contacted', { method: 'POST', headers });
    loadLeads(); loadDash();
  }

  useEffect(() => { if (token) { loadLeads(); loadDash(); } }, [token]);

  if (!token) return (
    <div style={{ maxWidth: 400, margin: '80px auto', padding: 20, fontFamily: 'system-ui' }}>
      <h1 style={{ fontSize: 24, marginBottom: 8 }}>🔗 FollowFlow</h1>
      <p style={{ color: '#888', fontSize: 14, marginBottom: 20 }}>Tidak ada lagi calon pelanggan yang terlupakan</p>
      <div style={{ background: '#f9f9f9', borderRadius: 12, padding: 16 }}>
        <input value={email} onChange={e => setEmail(e.target.value)} placeholder="Email" style={{ width: '100%', padding: 8, borderRadius: 8, border: '1px solid #ddd', marginBottom: 8 }} />
        <button onClick={login} style={{ width: '100%', padding: 10, background: '#4f46e5', color: 'white', border: 'none', borderRadius: 8, cursor: 'pointer' }}>Masuk</button>
      </div>
    </div>
  );

  return (
    <div style={{ maxWidth: 800, margin: '0 auto', padding: 20, fontFamily: 'system-ui' }}>
      <h1 style={{ fontSize: 20, marginBottom: 16 }}>🔗 FollowFlow</h1>
      {dash && (
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 8, marginBottom: 16 }}>
          {[['Total', dash.total], ['Baru', dash.new], ['Stale', dash.stale], ['Value', 'Rp ' + (dash.total_value || 0).toLocaleString('id-ID')]].map(([l, v]) => (
            <div key={l} style={{ background: '#f5f5f5', borderRadius: 8, padding: 12, textAlign: 'center' }}>
              <div style={{ fontSize: 20, fontWeight: 700 }}>{v}</div>
              <div style={{ fontSize: 11, color: '#888' }}>{l}</div>
            </div>
          ))}
        </div>
      )}
      <div style={{ background: '#f5f5f5', borderRadius: 12, padding: 12, marginBottom: 16 }}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr 1fr 80px', gap: 8 }}>
          <input value={newLead.name} onChange={e => setNewLead({...newLead, name: e.target.value})} placeholder="Nama" style={{ padding: 6, borderRadius: 6, border: '1px solid #ddd' }} />
          <input value={newLead.phone} onChange={e => setNewLead({...newLead, phone: e.target.value})} placeholder="Telepon" style={{ padding: 6, borderRadius: 6, border: '1px solid #ddd' }} />
          <input value={newLead.company} onChange={e => setNewLead({...newLead, company: e.target.value})} placeholder="Perusahaan" style={{ padding: 6, borderRadius: 6, border: '1px solid #ddd' }} />
          <input value={newLead.value} onChange={e => setNewLead({...newLead, value: parseInt(e.target.value) || 0 })} type="number" placeholder="Nilai" style={{ padding: 6, borderRadius: 6, border: '1px solid #ddd' }} />
          <button onClick={addLead} style={{ background: '#4f46e5', color: 'white', border: 'none', borderRadius: 6, cursor: 'pointer' }}>+ Tambah</button>
        </div>
      </div>
      <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 14 }}>
        <thead><tr style={{ borderBottom: '2px solid #eee' }}>
          <th style={{ textAlign: 'left', padding: 8 }}>Nama</th>
          <th style={{ textAlign: 'left', padding: 8 }}>Perusahaan</th>
          <th style={{ textAlign: 'left', padding: 8 }}>Status</th>
          <th style={{ textAlign: 'left', padding: 8 }}>Last Contact</th>
          <th></th>
        </tr></thead>
        <tbody>{leads.map(l => (
          <tr key={l.id} style={{ borderBottom: '1px solid #f0f0f0' }}>
            <td style={{ padding: 8 }}><b>{l.name}</b><br/><span style={{ fontSize: 12, color: '#888' }}>{l.phone || l.email || '-'}</span></td>
            <td style={{ padding: 8 }}>{l.company || '-'}</td>
            <td style={{ padding: 8 }}><span style={{ fontSize: 12, padding: '2px 8px', borderRadius: 4, background: l.status === 'new' ? '#dbeafe' : l.status === 'stale' ? '#fef3c7' : '#d1fae5' }}>{l.status}</span></td>
            <td style={{ padding: 8, fontSize: 12, color: '#888' }}>{new Date(l.last_contact).toLocaleDateString('id-ID')}</td>
            <td style={{ padding: 8 }}><button onClick={() => markContacted(l.id)} style={{ fontSize: 11, padding: '4px 8px', background: '#10b981', color: 'white', border: 'none', borderRadius: 4, cursor: 'pointer' }}>✓ Hubungi</button></td>
          </tr>
        ))}</tbody>
      </table>
    </div>
  );
}

export default App;
