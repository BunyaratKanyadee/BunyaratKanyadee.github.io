# BunyaratKanyadee.github.io

Persönliches Portfolio von Bunyarat Kanyadee – Lebenslauf, Skills und
Projekte. Läuft komplett statisch über GitHub Pages, kein Backend nötig.

## Live

Nach dem Deploy erreichbar unter: https://BunyaratKanyadee.github.io/

## Lokal ansehen

Einfach `index.html` im Browser öffnen – keine Installation nötig.

## Deploy auf GitHub Pages

Dieses Repo muss exakt `BunyaratKanyadee.github.io` heißen, damit GitHub
es automatisch als persönliche Seite veröffentlicht.

```bash
cd portfolio
git init
git add .
git commit -m "Portfolio: CV und Projekte"
git branch -M main
git remote add origin https://github.com/BunyaratKanyadee/BunyaratKanyadee.github.io.git
git push -u origin main
```

Danach in den Repo-Einstellungen auf GitHub: **Settings → Pages → Source:
Deploy from branch → main / (root)**. Nach 1-2 Minuten ist die Seite live.

## Die beiden verlinkten Projekte

Die Projekt-Karten auf der Seite verlinken auf zwei eigene Repos, die
genauso hochgeladen werden müssen:

**Reha Klinik Verwaltungssystem** (PHP/MySQL – nur Code-Link, kein
Live-Demo, da GitHub Pages kein PHP ausführen kann):
```bash
cd rehaklinik-project
git init
git add .
git commit -m "Reha Klinik Verwaltungssystem"
git branch -M main
git remote add origin https://github.com/BunyaratKanyadee/rehaklinik-verwaltung.git
git push -u origin main
```

**GoldTracker** (reines HTML/CSS/JS – läuft direkt als GitHub-Pages-Demo):
```bash
cd goldtracker
git init
git add .
git commit -m "GoldTracker"
git branch -M main
git remote add origin https://github.com/BunyaratKanyadee/goldtracker.git
git push -u origin main
```
Danach genauso **Settings → Pages → Source: Deploy from branch → main /
(root)** aktivieren – dann ist die Live-Demo unter
https://BunyaratKanyadee.github.io/goldtracker/ erreichbar, exakt der
Link, der schon auf der Portfolio-Seite hinterlegt ist.

Falls du die Repo-Namen `rehaklinik-verwaltung` oder `goldtracker` anders
nennst, die Links in `index.html` (Abschnitt „Projekte") entsprechend
anpassen.

## Struktur

```
├── index.html      CV, Skills, Projekte, Kontakt
├── css/style.css   Styling
└── js/script.js    Mobile-Nav-Toggle
```
