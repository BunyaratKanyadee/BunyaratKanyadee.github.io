// Steuert die Filter-Buttons in der Sidebar (Patienten- und Therapeuten-Seite).
// "all"    -> alle Karten anzeigen
// "az"     -> Karten alphabetisch nach Namen sortieren
// sonst    -> nur Karten mit passendem data-specialty anzeigen

document.addEventListener('DOMContentLoaded', () => {
  const filterBar = document.querySelector('.filter-bar');
  const list = document.querySelector('.kanban-column-list');
  if (!filterBar || !list) return;

  const buttons = filterBar.querySelectorAll('.filter-btn');

  function setActive(btn) {
    buttons.forEach((b) => b.classList.remove('active'));
    btn.classList.add('active');
  }

  function applyFilter(filter) {
    const cards = Array.from(list.querySelectorAll('.card'));

    if (filter === 'az') {
      cards
        .sort((a, b) => a.textContent.trim().localeCompare(b.textContent.trim(), 'de'))
        .forEach((card) => list.appendChild(card));
      cards.forEach((card) => { card.style.display = ''; });
      return;
    }

    cards.forEach((card) => {
      if (!filter || filter === 'all') {
        card.style.display = '';
      } else {
        card.style.display = card.dataset.specialty === filter ? '' : 'none';
      }
    });
  }

  buttons.forEach((btn) => {
    btn.addEventListener('click', () => {
      setActive(btn);
      applyFilter(btn.dataset.filter);
    });
  });

  const defaultBtn = filterBar.querySelector('#default-filter') || buttons[0];
  if (defaultBtn) {
    setActive(defaultBtn);
  }
});
