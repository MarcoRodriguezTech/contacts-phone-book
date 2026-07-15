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
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Phone Book — Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/png" href="icons/web-icon.png">

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
<script>
  (function() {
    const savedTheme = localStorage.getItem('phonebook-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = savedTheme || (prefersDark ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);
  })();
</script>
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <h2 class="sidebar-brand">
        <img src="icons/web-icon.png" alt="Phone Book Logo" class="brand-logo">
        Phone Book
      </h2>
      <p class="welcome">Hi, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
      <input type="text" id="search" placeholder="Search name or phone...">
      <button id="add-btn" class="primary">+ Add Contact</button>
      
      <div class="view-toggle">
        <button id="view-list" class="active">List</button>
        <button id="view-card">Cards</button>
      </div>

      <div class="directory-toggle">
        <div id="directory-list" class="directory-list">
          <button id="show-personal" class="directory-btn active" data-directory="personal">My Contacts</button>
          <button id="show-emergency" class="directory-btn" data-directory="emergency">Emergency List</button>
        </div>
      </div>

      <div class="sidebar-footer">
        <button id="theme-toggle-btn" class="theme-icon-btn" aria-label="Toggle theme">
          <svg class="theme-icon icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="4"></circle>
            <path d="M12 2v2"></path>
            <path d="M12 20v2"></path>
            <path d="m4.93 4.93 1.41 1.41"></path>
            <path d="m17.66 17.66 1.41 1.41"></path>
            <path d="M2 12h2"></path>
            <path d="M20 12h2"></path>
            <path d="m6.34 17.66-1.41 1.41"></path>
            <path d="m19.07 4.93-1.41 1.41"></path>
          </svg>

          <svg class="theme-icon icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
          </svg>
        </button>

        <a href="logout.php" id="logout-link" class="logout">
          <svg class="logout-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" x2="9" y1="12" y2="12"></line>
          </svg>
          Log Out
        </a>
      </div>
    </aside>

    <main class="content">
      <div class="content-header">
        <h1>Contacts</h1>
        <span class="content-count" id="contact-count"></span>
      </div>
      <div id="contact-container" class="list-view"></div>
    </main>
  </div>

  <div id="modal" class="modal-overlay hidden">
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

  <div id="delete-modal" class="modal-overlay hidden">
  <div class="modal-box delete-modal-box">
    <h2>Delete Contact</h2>
    <p>Are you sure you want to delete this contact? This action cannot be undone.</p>
    <div class="modal-actions">
      <button type="button" id="delete-cancel-btn">Cancel</button>
      <button type="button" id="delete-confirm-btn" class="danger-btn">Delete</button>
    </div>
  </div>
</div>

<div id="logout-modal" class="modal-overlay hidden">
  <div class="modal-box logout-modal-box">
    <h2>Log Out</h2>
    <p>Are you sure you want to log out of your personal directory?</p>
    <div class="modal-actions">
      <button type="button" id="logout-cancel-btn">Cancel</button>
      <button type="button" id="logout-confirm-btn" class="danger-btn">Log Out</button>
    </div>
  </div>
</div>

<script>
const container = document.getElementById('contact-container');
const modal = document.getElementById('modal');
const form = document.getElementById('contact-form');
const searchInput = document.getElementById('search');
const countLabel = document.getElementById('contact-count');

let iti;
const phoneInput = document.getElementById('f-phone');

document.addEventListener("DOMContentLoaded", () => {
  iti = window.intlTelInput(phoneInput, {
    initialCountry: "auto", 
    geoIpLookup: function(callback) {
      fetch("https://ipapi.co/json/")
        .then(res => res.json())
        .then(data => callback(data.country_code))
        .catch(() => callback("US")); 
    },
    preferredCountries: ["ph", "us", "gb"], 
    separateDialCode: true, 
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
  });
});

function openModal(contact) {
  form.reset();
  document.getElementById('modal-title').textContent = contact ? 'Edit Contact' : 'Add Contact';
  document.getElementById('f-id').value = contact?.id || '';
  document.getElementById('f-name').value = contact?.name || '';

  if (iti) {
    iti.setNumber(contact?.phone || ''); 
  } else {
    document.getElementById('f-phone').value = contact?.phone || '';
  }

  document.getElementById('f-email').value = contact?.email || '';
  document.getElementById('f-address').value = contact?.address || '';
  document.getElementById('f-notes').value = contact?.notes || '';
  modal.classList.remove('hidden');
}
function closeModal() { modal.classList.add('hidden'); }

document.getElementById('add-btn').onclick = () => openModal(null);
document.getElementById('cancel-btn').onclick = closeModal;
modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

let currentDirectory = 'personal'; 

async function loadContacts() {
  const q = searchInput.value.trim();
  const res = await fetch(`contacts.php?action=list&search=${encodeURIComponent(q)}&type=${currentDirectory}`);
  const contacts = await res.json();
  render(contacts);
}

function render(contacts) {
  container.innerHTML = '';
  
  countLabel.textContent = contacts.length
    ? contacts.length + (contacts.length === 1 ? ' contact' : ' contacts')
    : '';

  if (contacts.length === 0) {
    container.innerHTML = '<p class="empty">No contacts yet.</p>';
    return;
  }

  let lastLetter = null;
  contacts.forEach(c => {
    const letter = (c.name || '#').trim().charAt(0).toUpperCase() || '#';
    if (letter !== lastLetter) {
      const divider = document.createElement('div');
      divider.className = 'letter-divider';
      divider.textContent = letter;
      container.appendChild(divider);
      lastLetter = letter;
    }

    const card = document.createElement('div');
    card.className = `contact ${c.contact_type === 'emergency' ? 'emergency-card' : ''}`;
    card.innerHTML = `
      <div class="contact-main">
        <strong>${escapeHtml(c.name)}</strong>
        <span>${escapeHtml(c.phone)}</span>
        ${c.email ? `<span>${escapeHtml(c.email)}</span>` : ''}
        ${c.address ? `<span>${escapeHtml(c.address)}</span>` : ''}
        ${c.notes ? `<span class="notes">${escapeHtml(c.notes)}</span>` : ''}
      </div>
      <div class="contact-actions">
        ${c.contact_type === 'emergency' 
          ? '<span class="global-badge">Official</span>' 
          : `
            <button class="edit-btn">Edit</button>
            <button class="del-btn">Delete</button>
          `
        }
      </div>`;
    
    if (c.contact_type !== 'emergency') {
      card.querySelector('.edit-btn').onclick = () => openModal(c);
      card.querySelector('.del-btn').onclick = () => deleteContact(c.id);
    }
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
  if (iti) {
    data.set('phone', iti.getNumber()); 
  }
  const isEdit = !!data.get('id');
  data.set('action', isEdit ? 'edit' : 'add');
  const res = await fetch('contacts.php', { method: 'POST', body: data });
  const result = await res.json();
  if (result.error) { alert(result.error); return; }
  closeModal();
  loadContacts();
};

let contactIdToDelete = null;
const deleteModal = document.getElementById('delete-modal');
const deleteConfirmBtn = document.getElementById('delete-confirm-btn');
const deleteCancelBtn = document.getElementById('delete-cancel-btn');

function deleteContact(id) {
  contactIdToDelete = id;
  deleteModal.classList.remove('hidden');
}

deleteConfirmBtn.onclick = async () => {
  if (contactIdToDelete !== null) {
    const data = new FormData();
    data.set('action', 'delete');
    data.set('id', contactIdToDelete);
    await fetch('contacts.php', { method: 'POST', body: data });
    loadContacts();
  }
  closeDeleteModal();
};

function closeDeleteModal() {
  deleteModal.classList.add('hidden');
  contactIdToDelete = null;
}

deleteCancelBtn.onclick = closeDeleteModal;
deleteModal.addEventListener('click', e => { if (e.target === deleteModal) closeDeleteModal(); });

const logoutLink = document.getElementById('logout-link');
const logoutModal = document.getElementById('logout-modal');
const logoutConfirmBtn = document.getElementById('logout-confirm-btn');
const logoutCancelBtn = document.getElementById('logout-cancel-btn');

logoutLink.onclick = (e) => {
  e.preventDefault(); 
  logoutModal.classList.remove('hidden');
};

logoutConfirmBtn.onclick = () => {
  window.location.href = 'logout.php';
};

function closeLogoutModal() {
  logoutModal.classList.add('hidden');
}

logoutCancelBtn.onclick = closeLogoutModal;
logoutModal.addEventListener('click', e => { if (e.target === logoutModal) closeLogoutModal(); });

let searchTimer;
searchInput.addEventListener('input', () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(loadContacts, 250);
});

// list/card view toggle
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

// Standard Core Directory Toggle Logic
document.querySelectorAll('.directory-btn').forEach(btn => {
  btn.onclick = function() {
    document.querySelectorAll('.directory-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    currentDirectory = this.getAttribute('data-directory');
    loadContacts();
  };
});

const themeToggleBtn = document.getElementById('theme-toggle-btn');

function setTheme(theme) {
  document.documentElement.setAttribute('data-theme', theme);
  localStorage.setItem('phonebook-theme', theme);
}

themeToggleBtn.onclick = () => {
  const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  setTheme(newTheme);
};

// Initialize Theme
const savedTheme = localStorage.getItem('phonebook-theme');
if (savedTheme) {
  setTheme(savedTheme);
} else {
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  setTheme(prefersDark ? 'dark' : 'light');
}
loadContacts();
</script>
</body>
</html>