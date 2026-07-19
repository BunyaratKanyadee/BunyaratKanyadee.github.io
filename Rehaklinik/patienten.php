<?php
require_once __DIR__ . '/Server/auth.php';
require_login();
require_once __DIR__ . '/Server/get_patient.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Patienten Übersicht</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/patientenstyle.css" />
</head>
<body>
  <header class="header">
    <a href="index.php" class="logo">Thera</a>
    <nav class="navbar">
      <a href="index.php">Home</a>
      <a href="therapeuten.php">Therapeuten</a>
      <a href="patienten.php">Patienten</a>
    </nav>
    <div class="icons">
      <div class="fas fa-search" id="search-btn"></div>
      <div class="fas fa-bars" id="menu-btn"></div>
      <div class="fas fa-sign-out-alt" id="logout-btn"></div>
    </div>
    <div class="search-form">
      <input type="search" id="search-box" placeholder="Name eingeben..." />
      <label for="search-box" class="fas fa-search"></label>
    </div>
  </header>

  <div class="main-content">
    <div class="left-panel">
      <aside class="sidebar">
        <div class="filter-bar">
          <button class="filter-btn" data-filter="all" id="default-filter">Alle</button>
          <button class="filter-btn" data-filter="az">A-Z</button>
          <button class="filter-btn" data-filter="Einzeltherapie">Einzeltherapie</button>
          <button class="filter-btn" data-filter="Gruppentherapie">Gruppentherapie</button>
          <button class="filter-btn" data-filter="Bewegungstherapie">Bewegungstherapie</button>
          <button class="filter-btn" data-filter="Psychoedukation">Psychoedukation</button>
        </div>
      </aside>
    </div>

    <div class="kanban-wrapper">
      <div class="kanban-title-row">
        <h2 class="kanban-title">Patientenliste</h2>
        <button type="button" id="add-patient-btn" class="btn add-btn">+ Neuer Patient</button>
      </div>
      <div class="kanban">
        <div class="kanban-column" data-column="1">
          <ul class="kanban-column-list">
            <?php while ($row = $patient_result->fetch_assoc()) { ?>
              <li class="card"
                  data-id="<?php echo (int)$row['patient_id']; ?>"
                  data-vorname="<?php echo htmlspecialchars($row['vorname'] ?? ''); ?>"
                  data-nachname="<?php echo htmlspecialchars($row['nachname'] ?? ''); ?>"
                  data-geburtsdatum="<?php echo htmlspecialchars($row['geburtsdatum'] ?? ''); ?>"
                  data-versichertennummer="<?php echo htmlspecialchars($row['versichertennummer'] ?? ''); ?>"
                  data-kontaktinfo="<?php echo htmlspecialchars($row['kontaktinfo'] ?? ''); ?>"
                  data-specialty="<?php echo htmlspecialchars($row['therapieart'] ?? ''); ?>">
                <?php echo htmlspecialchars($row['vorname'] . ' ' . $row['nachname']); ?>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>

      <!-- Popup: dient sowohl zum Anlegen als auch zum Bearbeiten/Löschen -->
      <div id="patient-popup" class="popup-overlay" style="display:none;">
        <div class="popup-content">
          <span id="popup-close" class="popup-close">&times;</span>
          <h2 id="popup-title">Neuer Patient</h2>
          <form id="patient-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_csrf_token()); ?>">
            <input type="hidden" name="patient_id">
            <label>Vorname
              <input type="text" name="vorname" required>
            </label>
            <label>Nachname
              <input type="text" name="nachname" required>
            </label>
            <label>Geburtsdatum
              <input type="date" name="geburtsdatum">
            </label>
            <label>Versichertennummer
              <input type="text" name="versichertennummer">
            </label>
            <label>Kontaktinfo
              <textarea name="kontaktinfo"></textarea>
            </label>
            <label>Therapieart
              <select name="therapieart">
                <option value="">–</option>
                <option value="Einzeltherapie">Einzeltherapie</option>
                <option value="Gruppentherapie">Gruppentherapie</option>
                <option value="Bewegungstherapie">Bewegungstherapie</option>
                <option value="Psychoedukation">Psychoedukation</option>
              </select>
            </label>
            <p id="popup-error" class="error-message" style="display:none;"></p>
            <div class="popup-actions">
              <button type="submit" class="btn">Speichern</button>
              <button type="button" id="popup-delete" class="btn btn-danger" style="display:none;">Löschen</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="js/patientenscript.js"></script>
  <script src="js/search.js"></script>
  <script src="js/filter.js"></script>
  <script src="js/patienten-crud.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const logoutBtn = document.getElementById('logout-btn');
      if (logoutBtn) {
        logoutBtn.onclick = () => {
          if (confirm("Möchten Sie ausloggen?")) {
            window.location.href = "logout.php";
          }
        };
      }
    });
    let lastScrollTop = 0;
    const header = document.querySelector('.header');
    window.addEventListener('scroll', function() {
      let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
      if (currentScroll > lastScrollTop) {
        header.classList.add('hidden');
        header.classList.remove('scrolled');
      } else {
        header.classList.remove('hidden');
        header.classList.add('scrolled');
      }
      lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });
  </script>
</body>
</html>
