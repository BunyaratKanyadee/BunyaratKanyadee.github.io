# GoldTracker

Dashboard-Frontend für Edelmetallpreise mit Live-Chart (TradingView), einem
Preisrechner in mehreren Währungen und Light-/Dark-Mode.

## Live-Demo

https://BunyaratKanyadee.github.io/goldtracker/

Läuft komplett im Browser (kein Backend nötig) – daher direkt über GitHub
Pages nutzbar. Login-Daten für die Demo: `test@test.com` / `1234`
(steht auch auf der Login-Seite selbst).

## Was beim Aufräumen behoben wurde

- Pfade zu den Stylesheets waren relativ zu einer Unterordner-Struktur
  (`../css/...`), die es im Repo nicht gab – auf `css/...` korrigiert,
  damit sie bei GitHub Pages direkt funktionieren.
- `login.html` in `index.html` umbenannt, damit GitHub Pages die Seite
  automatisch als Startseite ausliefert.
- `@keyframes` hieß `brownShift`, wurde aber als `creamShift` aufgerufen –
  die Hintergrund-Animation lief nie. Umbenannt, damit sie greift.
- Doppelt eingebundenes `chart.js`-Script entfernt.
- Fehlermeldung des Preisrechners verständlicher gemacht, falls die
  Live-Preis-API nicht antwortet.
- Demo-Zugangsdaten stehen jetzt sichtbar auf der Login-Seite (Login ist
  rein clientseitig geprüft, kein echtes Backend).

## Bekannte Einschränkung

Der Preisrechner ruft `https://freegoldapi.com/data/latest.json` auf. Das
ist keine bekannte, dokumentierte API – falls sie nicht (mehr) erreichbar
ist, zeigt der Rechner eine Fehlermeldung statt eines Preises. Für einen
zuverlässigen Live-Preis müsste hier ein echter Anbieter eingebunden
werden, z. B. [metals-api.com](https://metals-api.com) oder
[goldapi.io](https://www.goldapi.io) (beide benötigen einen kostenlosen
API-Key).
