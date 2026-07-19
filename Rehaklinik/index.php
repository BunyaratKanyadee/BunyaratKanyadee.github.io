<?php
require_once __DIR__ . '/Server/auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reha Klinik - Startseite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/homestyle.css">
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
            <input type="search" id="search-box" placeholder="Name eingeben...">
            <label for="search-box" class="fas fa-search"></label>
        </div>
    </header>

    <!-- home section -->
    <section class="home" id="home">
        <div class="content">
            <h3>Therapie Klinik</h3>
            <p>„Dein Weg zur Heilung beginnt hier – in einer geschützten und unterstützenden Umgebung, die Körper, Geist und Seele gleichermaßen anspricht. Mit kreativen Methoden wie Kunsttherapie, Bewegung und einfühlsamer, professioneller Begleitung fördern wir dein emotionales Wohlbefinden und stärken deine persönliche Entwicklung.“
            </p>
        </div>
    </section>

    <!-- therapy types section -->
    <section class="menu" id="menu">
        <h1 class="heading">kunsttherapie</h1>
        <div class="box-container">
            <div class="box">
                <img src="images/1.jpeg" alt="Einzeltherapie">
                <h3>Einzeltherapie</h3>
            </div>
            <div class="box">
                <img src="images/bg6.png" alt="Gruppentherapie">
                <h3>Gruppentherapie</h3>
            </div>
            <div class="box">
                <img src="images/3.png" alt="Bewegungstherapie">
                <h3>Bewegungstherapie</h3>
            </div>
            <div class="box">
                <img src="images/4.png" alt="Psychoedukation">
                <h3>Psychoedukation</h3>
            </div>
        </div>
    </section>

    <script src="js/homescript.js"></script>
    <script src="js/search.js"></script>
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
</body>
</html>
