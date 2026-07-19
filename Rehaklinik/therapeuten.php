<?php
require_once __DIR__ . '/Server/auth.php';
require_login();
require_once __DIR__ . '/Server/get_therapeut.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Therapeuten Übersicht</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/therapeutenstyle.css" />
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
      <h2 class="kanban-title">Therapeutenliste</h2>
      <div class="kanban">
        <div class="kanban-column" data-column="1">
          <ul class="kanban-column-list">
            <?php while ($row = $therapeut_result->fetch_assoc()) { ?>
              <li class="card" data-specialty="<?php echo htmlspecialchars($row['fachgebiet'] ?? ''); ?>"><?php echo htmlspecialchars($row['vorname'] . ' ' . $row['nachname']); ?></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="calendar light">
        <div class="calendar_header">
          <h1 class="header_title">Welcome</h1>
          <p class="header_copy">Calendar Plan</p>
        </div>
        <div class="calendar_plan">
          <div class="cl_plan">
            <div class="cl_title">Today</div>
            <div class="cl_copy" id="today-date"></div>
          </div>
        </div>
        <div class="calendar_events">
          <p class="ce_title">Upcoming Events</p>
          <div class="event_item">
            <div class="ei_Dot dot_active"></div>
            <div class="ei_Title">10:30 am</div>
            <div class="ei_Copy">Monday briefing with the team</div>
          </div>
          <div class="event_item">
            <div class="ei_Dot"></div>
            <div class="ei_Title">12:00 pm</div>
            <div class="ei_Copy">Lunch with the team</div>
          </div>
          <div class="event_item">
            <div class="ei_Dot"></div>
            <div class="ei_Title">13:00 pm</div>
            <div class="ei_Copy">Meet with the client for final design</div>
          </div>
          <div class="event_item">
            <div class="ei_Dot"></div>
            <div class="ei_Title">14:30 pm</div>
            <div class="ei_Copy">Plan event to inspire students</div>
          </div>
          <div class="event_item">
            <div class="ei_Dot"></div>
            <div class="ei_Title">15:30 pm</div>
            <div class="ei_Copy">Add more events to the calendar</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="js/therapeutenscript.js"></script>
  <script src="js/search.js"></script>
  <script src="js/filter.js"></script>
  <script>
    document.getElementById('logout-btn').onclick = () => {
      if (confirm("Möchten Sie ausloggen?")) {
        window.location.href = "logout.php";
      }
    };
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
  <script>
    function getOrdinalSuffix(day) {
      if (day > 3 && day < 21) return 'th';
      switch (day % 10) {
        case 1: return 'st';
        case 2: return 'nd';
        case 3: return 'rd';
        default: return 'th';
      }
    }
    function formatDate(date) {
      const day = date.getDate();
      const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
      ];
      const month = monthNames[date.getMonth()];
      const year = date.getFullYear();
      return `${day}${getOrdinalSuffix(day)} ${month} ${year}`;
    }
    document.addEventListener('DOMContentLoaded', () => {
      const todayDateElem = document.getElementById('today-date');
      if (todayDateElem) {
        const today = new Date();
        todayDateElem.textContent = formatDate(today);
      }
    });
  </script>
</body>
</html>
