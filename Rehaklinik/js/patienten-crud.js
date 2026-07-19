document.addEventListener('DOMContentLoaded', () => {
  const popup = document.getElementById('patient-popup');
  const form = document.getElementById('patient-form');
  const popupTitle = document.getElementById('popup-title');
  const popupError = document.getElementById('popup-error');
  const deleteBtn = document.getElementById('popup-delete');
  const closeBtn = document.getElementById('popup-close');
  const addBtn = document.getElementById('add-patient-btn');
  const list = document.querySelector('.kanban-column-list');

  if (!popup || !form || !list) return;

  function showError(message) {
    popupError.textContent = message;
    popupError.style.display = 'block';
  }

  function openPopup(mode, card) {
    popupError.style.display = 'none';
    form.reset();

    if (mode === 'edit' && card) {
      popupTitle.textContent = 'Patient bearbeiten';
      form.patient_id.value = card.dataset.id;
      form.vorname.value = card.dataset.vorname || '';
      form.nachname.value = card.dataset.nachname || '';
      form.geburtsdatum.value = card.dataset.geburtsdatum || '';
      form.versichertennummer.value = card.dataset.versichertennummer || '';
      form.kontaktinfo.value = card.dataset.kontaktinfo || '';
      form.therapieart.value = card.dataset.specialty || '';
      deleteBtn.style.display = 'inline-block';
    } else {
      popupTitle.textContent = 'Neuer Patient';
      form.patient_id.value = '';
      deleteBtn.style.display = 'none';
    }

    popup.style.display = 'flex';
    form.vorname.focus();
  }

  function closePopup() {
    popup.style.display = 'none';
  }

  function applyPatientToCard(card, patient) {
    card.dataset.id = patient.patient_id;
    card.dataset.vorname = patient.vorname;
    card.dataset.nachname = patient.nachname;
    card.dataset.geburtsdatum = patient.geburtsdatum || '';
    card.dataset.versichertennummer = patient.versichertennummer || '';
    card.dataset.kontaktinfo = patient.kontaktinfo || '';
    card.dataset.specialty = patient.therapieart || '';
    card.textContent = `${patient.vorname} ${patient.nachname}`;
  }

  function renderCard(patient) {
    const li = document.createElement('li');
    li.className = 'card';
    applyPatientToCard(li, patient);
    return li;
  }

  if (addBtn) {
    addBtn.addEventListener('click', () => openPopup('create'));
  }

  list.addEventListener('click', (e) => {
    const card = e.target.closest('.card');
    if (card) openPopup('edit', card);
  });

  closeBtn.addEventListener('click', closePopup);
  popup.addEventListener('click', (e) => {
    if (e.target === popup) closePopup();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && popup.style.display !== 'none') closePopup();
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const isEdit = Boolean(form.patient_id.value);
    const formData = new FormData(form);
    formData.set('action', isEdit ? 'update' : 'create');

    try {
      const res = await fetch('Server/patient_actions.php', { method: 'POST', body: formData });
      const data = await res.json();

      if (!data.success) {
        showError(data.error || 'Es ist ein Fehler aufgetreten.');
        return;
      }

      if (isEdit) {
        const card = list.querySelector(`.card[data-id="${data.patient.patient_id}"]`);
        if (card) applyPatientToCard(card, data.patient);
      } else {
        list.appendChild(renderCard(data.patient));
      }
      closePopup();
    } catch (err) {
      showError('Netzwerkfehler – bitte erneut versuchen.');
    }
  });

  deleteBtn.addEventListener('click', async () => {
    const patientId = form.patient_id.value;
    if (!patientId) return;
    if (!confirm('Diesen Patienten wirklich löschen?')) return;

    const formData = new FormData();
    formData.set('action', 'delete');
    formData.set('patient_id', patientId);
    formData.set('csrf_token', form.csrf_token.value);

    try {
      const res = await fetch('Server/patient_actions.php', { method: 'POST', body: formData });
      const data = await res.json();

      if (!data.success) {
        showError(data.error || 'Löschen fehlgeschlagen.');
        return;
      }

      const card = list.querySelector(`.card[data-id="${patientId}"]`);
      if (card) card.remove();
      closePopup();
    } catch (err) {
      showError('Netzwerkfehler – bitte erneut versuchen.');
    }
  });
});
