# Reha Klinik – Verwaltungssystem

Ein kleines PHP/MySQL-Projekt zur Verwaltung von Patienten und Therapeuten
einer Reha-Klinik ("Thera"). Enthält Login-Maske, Startseite, sowie
datenbankgestützte Übersichtsseiten für Patienten und Therapeuten.

## Struktur

```
├── index.php                 Startseite (login-geschützt)
├── login.html                 Login-Maske (Frontend)
├── login.php                  Prüft Login-Daten, startet Session
├── logout.php                 Beendet die Session
├── patienten.php              Patientenliste (DB-gestützt, login-geschützt, Suche/Filter/CRUD)
├── therapeuten.php            Therapeutenliste (DB-gestützt, login-geschützt, Suche/Filter)
├── Server/
│   ├── connection.php          DB-Verbindung (liest Zugangsdaten aus ENV)
│   ├── auth.php                 Session-Check / require_login() / CSRF-Token
│   ├── create_user.php          CLI-Skript zum Anlegen eines Login-Benutzers
│   ├── get_patient.php          Liefert alle Patienten
│   ├── get_therapeut.php        Liefert alle Therapeuten
│   └── patient_actions.php      CRUD-Endpunkt (create/update/delete), JSON, login+CSRF-geschützt
├── css/                         Stylesheets
├── js/
│   ├── search.js                 Such-Toggle + Live-Filter der Karten nach Name
│   ├── filter.js                 Sidebar-Filter (Alle/A-Z/Therapieart) + Sortierung
│   └── patienten-crud.js         Popup-Formular: Anlegen/Bearbeiten/Löschen per fetch()
├── images/                      (siehe TODO – Dateien fehlen)
├── Datenbank/rehaklinik0.sql    DB-Schema (phpMyAdmin-Export, inkl. `benutzer`- und `patient.therapieart`)
├── prototypes/                   statische HTML-Entwürfe ohne DB-Anbindung
└── .env.example                   Vorlage für benötigte Umgebungsvariablen
```

## Setup

1. Datenbank anlegen und Schema importieren:
   ```
   mysql -u root -p < Datenbank/rehaklinik0.sql
   ```
2. `.env.example` nach `.env` kopieren und Zugangsdaten eintragen (siehe
   Hinweis unten – dein Server muss die Variablen tatsächlich laden, z. B.
   über Apache `SetEnv` oder ein kleines Bootstrap-Script mit
   [phpdotenv](https://github.com/vlucas/phpdotenv), da PHP `.env`-Dateien
   nicht automatisch einliest).
3. Einen Login-Benutzer anlegen (es gibt standardmäßig keinen!):
   ```
   php Server/create_user.php max@example.com "einSicheresPasswort" "Max Mustermann"
   ```
   Das Passwort wird dabei sicher gehasht (`password_hash`) in die Tabelle
   `benutzer` geschrieben – nirgendwo im Klartext gespeichert.
4. Projekt in den Webroot (z. B. XAMPP `htdocs`) legen und `login.html`
   im Browser öffnen. Mit den Daten aus Schritt 3 einloggen.

## Login, Suche, Filter & Patienten-CRUD

- **Login**: `login.html` sendet an `login.php`, das die E-Mail/Passwort-
  Kombination gegen die `benutzer`-Tabelle prüft (`password_verify`) und
  bei Erfolg eine Session startet. `index.php`, `patienten.php` und
  `therapeuten.php` sind über `require_login()` geschützt und leiten ohne
  gültige Session zurück zu `login.html`. Der Logout-Button ruft `logout.php`
  auf, das die Session beendet.
- **Suche**: Das Lupe-Icon im Header blendet das Suchfeld ein/aus; Tippen
  darin filtert die sichtbaren Patienten-/Therapeuten-Karten live nach
  Name (`js/search.js`).
- **Sidebar-Filter** (`js/filter.js`, betrifft beide Listen-Seiten): "Alle"
  zeigt wieder alle Karten, "A-Z" sortiert alphabetisch, die übrigen Buttons
  filtern nach Therapieart. Dafür vergleicht das Skript den Button-Wert mit
  `data-specialty` auf jeder Karte – bei Therapeuten kommt der Wert aus
  `fachgebiet`, bei Patienten aus dem neuen Feld `patient.therapieart`
  (siehe unten). Der Filter funktioniert nur, wenn der Text exakt
  übereinstimmt (z. B. `"Einzeltherapie"`).
- **Patienten-CRUD** (`patienten.php` + `Server/patient_actions.php` +
  `js/patienten-crud.js`): "+ Neuer Patient" öffnet ein leeres Formular,
  ein Klick auf eine bestehende Karte öffnet dasselbe Formular vorausgefüllt
  zum Bearbeiten (inkl. "Löschen"-Button). Anlegen/Ändern/Löschen läuft per
  `fetch()` gegen `Server/patient_actions.php`, das JSON zurückgibt und die
  Karte im DOM aktualisiert – kein Full-Page-Reload. Der Endpunkt prüft
  Login-Status und ein CSRF-Token (`get_csrf_token()` in `Server/auth.php`)
  und fängt den Fall ab, dass ein Patient wegen bestehender Termine
  (Fremdschlüssel `termin.patient_id`) nicht gelöscht werden kann.
- **Schema-Ergänzung**: `patient` hat jetzt eine Spalte `therapieart`
  (varchar, optional) – im Original gab es kein Feld, über das man Patienten
  direkt einer Therapieart zuordnen konnte (das ging nur indirekt über
  `termin`/`therapieart`). Für die Sidebar-Filterung war ein direktes Feld
  am Patienten die pragmatischste Lösung, analog zu `therapeut.fachgebiet`.
  Wer die Datenbank aus einem alten `rehaklinik0.sql`-Export bereits
  importiert hat, muss die Spalte manuell nachziehen:
  ```sql
  ALTER TABLE patient ADD COLUMN therapieart VARCHAR(100) DEFAULT NULL;
  ```

## Was beim Aufräumen behoben wurde

- **Absolute Windows-Pfade entfernt**: `Patienten.php`/`Therapeuten.php`
  banden Server-Skripte über feste Pfade wie
  `C:\xampp\htdocs\Project\Server\get_patient.php` ein – das funktioniert
  nur auf dem PC des Original-Autors. Jetzt per `require_once __DIR__ . '/...'`.
- **Kaputte HTML-Verschachtelung**: In beiden Listen wurde pro Datensatz ein
  eigenes `<ul>` geöffnet und wieder geschlossen (bzw. `</ul>` landete
  mitten in der `while`-Schleife) – dadurch ungültiges HTML und
  unvorhersehbares Styling. Jetzt umschließt genau ein `<ul>` die ganze Liste.
  - **Falscher DOM-Ziel-ID beim Event-Fetch**: Im Therapeuten-Prototyp wurde
   `#event-list` gesucht, obwohl das Element `#calendar-events` heißt –
   der Code wäre nie ausgeführt worden.
- **Zugangsdaten nicht mehr hart codiert**: `connection.php` liest
  Host/User/Passwort/DB-Name jetzt aus Umgebungsvariablen statt sie im
  Klartext im Code zu haben.
- **XSS-Lücke geschlossen**: Patienten-/Therapeutennamen werden jetzt mit
  `htmlspecialchars()` ausgegeben statt roh ins HTML geschrieben; im
  Kalender-Prototyp wird `textContent` statt `innerHTML` verwendet.
- **Groß-/Kleinschreibung vereinheitlicht**: Dateien hießen teils
  `Patienten.php`, teils verlinkten Seiten auf `patienten.html` –
  auf Linux-Servern (case-sensitive) wären das kaputte Links gewesen.
  Alle Dateinamen sind jetzt konsequent klein geschrieben.
- **Tote/auskommentierte Codeblöcke entfernt** aus `get_patient.php` und
  `get_therapeut.php`.
- **Verwirrende Variablennamen korrigiert**: `get_patient.php` befüllte
  eine Variable namens `$featured_therapeut` (copy-paste-Rest) – heißt
  jetzt `$patient_result`.
- **CSS-Bugs behoben**:
  - `calc(100vh-9.5rem)` ohne Leerzeichen ist ungültiges CSS → zu
    `calc(100vh - 9.5rem)` korrigiert.
  - `.kanban-wrapper` in zwei Stylesheets hatte mehrere widersprüchliche
    `width`-Angaben hintereinander (Reste von Experimenten) – auf die
    jeweils wirksame Deklaration reduziert.
  - `@keyframes` hieß `brownShift`, wurde aber als `creamShift`
    aufgerufen – die Animation lief nie. Umbenannt, damit sie greift.
  - Doppelter/falsch geschriebener Selektor `#calender-title` (Tippfehler)
    mit `#calendar-title` zusammengeführt.
- **Startseiten-Link korrigiert**: Navigation verwies auf `homepage.html`,
  das es nicht gab – zeigt jetzt auf `index.php`.
- **.gitignore und .env.example ergänzt**, damit keine Zugangsdaten
  versehentlich committet werden.

## Bekannte Lücken (nicht automatisch behoben)

- **`patientenscript.js`, `therapeutenscript.js`, `homescript.js` und
  `images/*` fehlen weiterhin** – sie wurden im Original referenziert,
  waren aber im hochgeladenen Dokument nicht enthalten. Die `<script>`-Tags
  dafür bleiben im Code stehen (schaden nicht, laden nur ins Leere);
  Ordner sind als Platzhalter angelegt.
- **Kein "Passwort vergessen"-Flow und kein Rate-Limiting** auf `login.php`.
  Für ein reines Portfolio-Demo unkritisch, für einen echten Einsatz mit
  echten Patientendaten würde ich das nachrüsten.
- **Nur Patienten haben CRUD**, Therapeuten sind weiterhin nur eine
  Leseliste (nicht angefragt – lässt sich aber nach demselben Muster
  ergänzen, falls gewünscht).
- Die statischen Prototyp-Seiten (`prototypes/`) enthalten nur
  Demo-/Platzhalterdaten, sind unabhängig von der Datenbank und haben
  kein CRUD (nur Suche/Filter-Skripte eingebunden).
