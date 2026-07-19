// Wird auf allen Seiten eingebunden, die eine .search-form im Header haben.
// - Klick auf die Lupe blendet das Suchfeld ein/aus.
// - Tippen im Suchfeld filtert die sichtbaren Patienten-/Therapeuten-Karten
//   (.card) nach Name, ohne einen Server-Request zu brauchen.

document.addEventListener('DOMContentLoaded', () => {
  const searchBtn = document.getElementById('search-btn');
  const searchForm = document.querySelector('.search-form');
  const searchBox = document.getElementById('search-box');

  if (searchBtn && searchForm) {
    searchBtn.addEventListener('click', () => {
      searchForm.classList.toggle('active');
      if (searchForm.classList.contains('active') && searchBox) {
        searchBox.focus();
      }
    });
  }

  if (searchBox) {
    searchBox.addEventListener('input', () => {
      const query = searchBox.value.trim().toLowerCase();
      const cards = document.querySelectorAll('.kanban-column-list .card');

      cards.forEach((card) => {
        const name = card.textContent.trim().toLowerCase();
        card.style.display = name.includes(query) ? '' : 'none';
      });
    });
  }
});
