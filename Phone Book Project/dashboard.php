<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Phone Book — Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <h2>📇 Phone Book</h2>
      <p class="welcome">Hi, <?= htmlspecialchars($_SESSION['username']) ?></p>
      <input type="text" id="search" placeholder="Search name or phone...">
      <button id="add-btn" class="primary">+ Add Contact</button>
      <div class="view-toggle">
        <button id="view-list" class="active">List</button>
        <button id="view-card">Cards</button>
      </div>
      <a href="logout.php" class="logout">Log Out</a>
    </aside>

    <main class="content">
      <h1>Contacts</h1>
      <div id="contact-container" class="list-view"></div>
    </main>
  </div>

  <!-- Add/Edit modal -->
  <div id="modal" class="modal hidden">
    <div class="modal-box">
      <h2 id="modal-title">Add Contact</h2>
      <form id="contact-form">
        <input type="hidden" name="id" id="f-id">
        <label>Name <input type="text" name="name" id="f-name" required></label>
        <label>Phone <input type="text" name="phone" id="f-phone" required></label>
        <label>Email <input type="email" name="email" id="f-email"></label>
        <label>Address <input type="text" name="address" id="f-address"></label>
        <label>Notes <input type="text" name="notes" id="f-notes"></label>
        <div class="modal-actions">
          <button type="button" id="cancel-btn">Cancel</button>
          <button type="submit" class="primary">Save</button>
        </div>
      </form>
    </div>
  </div>

<script>
const container = document.getElementById('contact-container');
const modal = document.getElementById('modal');
const form = document.getElementById('contact-form');
const searchInput = document.getElementById('search');

function openModal(contact) {
  form.reset();
  document.getElementById('modal-title').textContent = contact ? 'Edit Contact' : 'Add Contact';
  document.getElementById('f-id').value = contact?.id || '';
  document.getElementById('f-name').value = contact?.name || '';
  document.getElementById('f-phone').value = contact?.phone || '';
  document.getElementById('f-email').value = contact?.email || '';
  document.getElementById('f-address').value = contact?.address || '';
  document.getElementById('f-notes').value = contact?.notes || '';
  modal.classList.remove('hidden');
}
function closeModal() { modal.classList.add('hidden'); }

document.getElementById('add-btn').onclick = () => openModal(null);
document.getElementById('cancel-btn').onclick = closeModal;
modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

async function loadContacts() {
  const q = searchInput.value.trim();
  const res = await fetch('contacts.php?action=list&search=' + encodeURIComponent(q));
  const contacts = await res.json();
  render(contacts);
}

function render(contacts) {
  container.innerHTML = '';
  if (contacts.length === 0) {
    container.innerHTML = '<p class="empty">No contacts yet.</p>';
    return;
  }
  contacts.forEach(c => {
    const card = document.createElement('div');
    card.className = 'contact';
    card.innerHTML = `
      <div class="contact-main">
        <strong>${escapeHtml(c.name)}</strong>
        <span>${escapeHtml(c.phone)}</span>
        ${c.email ? `<span>${escapeHtml(c.email)}</span>` : ''}
        ${c.address ? `<span>${escapeHtml(c.address)}</span>` : ''}
        ${c.notes ? `<span class="notes">${escapeHtml(c.notes)}</span>` : ''}
      </div>
      <div class="contact-actions">
        <button class="edit-btn">Edit</button>
        <button class="del-btn">Delete</button>
      </div>`;
    card.querySelector('.edit-btn').onclick = () => openModal(c);
    card.querySelector('.del-btn').onclick = () => deleteContact(c.id);
    container.appendChild(card);
  });
}

function escapeHtml(s) {
  const div = document.createElement('div');
  div.textContent = s;
  return div.innerHTML;
}

form.onsubmit = async (e) => {
  e.preventDefault();
  const data = new FormData(form);
  const isEdit = !!data.get('id');
  data.set('action', isEdit ? 'edit' : 'add');
  const res = await fetch('contacts.php', { method: 'POST', body: data });
  const result = await res.json();
  if (result.error) { alert(result.error); return; }
  closeModal();
  loadContacts();
};

async function deleteContact(id) {
  if (!confirm('Delete this contact?')) return;
  const data = new FormData();
  data.set('action', 'delete');
  data.set('id', id);
  await fetch('contacts.php', { method: 'POST', body: data });
  loadContacts();
}

let searchTimer;
searchInput.addEventListener('input', () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(loadContacts, 250);
});

// list/card view toggle, remembered per browser
const viewListBtn = document.getElementById('view-list');
const viewCardBtn = document.getElementById('view-card');
function setView(view) {
  container.className = view + '-view';
  viewListBtn.classList.toggle('active', view === 'list');
  viewCardBtn.classList.toggle('active', view === 'card');
  localStorage.setItem('phonebook-view', view);
}
viewListBtn.onclick = () => setView('list');
viewCardBtn.onclick = () => setView('card');
setView(localStorage.getItem('phonebook-view') || 'list');

loadContacts();
</script>
</body>
</html>
