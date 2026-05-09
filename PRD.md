# Product Requirements Document (PRD) — Hobby-Tracker

**Projektname:** Ausbildungs-Laravel-Kurs (Hobby-Tracker)
**Version:** 1.0
**Stand:** 2026-05-09
**Autor:** c.ilgner
**Status:** In Entwicklung (Ausbildungsprojekt)

---

## 1. Überblick

### 1.1 Ziel des Produkts
Der Hobby-Tracker ist eine webbasierte Plattform, auf der registrierte Nutzer eigene Hobbys anlegen, beschreiben, mit Bildern versehen und über Tags kategorisieren können. Andere Nutzer können fremde Hobby-Profile einsehen und nach Tags filtern. Das Projekt entsteht im Rahmen einer Ausbildung und dient primär dem praktischen Erlernen des Laravel-Frameworks (Routing, Eloquent, Auth, Middleware, Gates, Migrations, Blade, Bildverarbeitung).

### 1.2 Hintergrund
Das Projekt ist ein klassisches CRUD-Lehrprojekt aus einem Laravel-Kurs. Es wurde ursprünglich auf Laravel 8 angelegt (Migrations aus 2021) und auf **Laravel 12 / PHP 8.2** aktualisiert. Es umfasst Authentifizierung über `laravel/ui`, ein Many-to-Many-Mapping (Hobby ↔ Tag), ein One-to-Many-Mapping (User → Hobbys) sowie Bild-Resizing über `intervention/image`.

### 1.3 Zielgruppe
- **Primär:** Auszubildende / Lernende, die das Projekt als Lernartefakt entwickeln.
- **Sekundär (im Sinne fiktiver Endnutzer):** Privatpersonen, die ihre Hobbys digital dokumentieren und mit anderen teilen wollen.
- **Admin-Rolle:** Verwaltung von Tags und Bearbeitung fremder Inhalte.

---

## 2. Geschäftliche Anforderungen / Lernziele

| ID  | Anforderung                                                              | Priorität |
|-----|--------------------------------------------------------------------------|-----------|
| B1  | Vermittlung der Laravel-Grundlagen (MVC, Routing, Eloquent, Blade)       | Muss      |
| B2  | Implementierung einer Benutzer-Authentifizierung mit Rollen              | Muss      |
| B3  | Praxis mit Datenbank-Beziehungen (1:n, n:m)                              | Muss      |
| B4  | Datei-Upload und serverseitige Bildverarbeitung (Thumbnail, Pixel-Vorschau) | Muss   |
| B5  | Autorisierung über Gates / Policies                                      | Muss      |
| B6  | Einsatz von Migrations und Seedern                                       | Muss      |
| B7  | Modernes Frontend-Tooling (Vite, npm, Bootstrap)                         | Soll      |
| B8  | CI/Dependency-Management über Dependabot                                 | Soll      |

---

## 3. Funktionale Anforderungen

### 3.1 Benutzerverwaltung & Authentifizierung
| ID    | Beschreibung |
|-------|--------------|
| F-A1  | Nutzer können sich über Name, E-Mail und Passwort registrieren (`laravel/ui`). |
| F-A2  | Nutzer können sich anmelden und abmelden. |
| F-A3  | Passwortänderung / Passwort-Reset über Standard-Laravel-Auth. |
| F-A4  | Nicht angemeldete Nutzer haben Zugriff auf öffentliche Seiten (Startseite, Info, Kontakt, Impressum, Tag-Übersicht, Hobby-Detailansicht). |
| F-A5  | Geschützte Aktionen (Hobby anlegen, bearbeiten, löschen, Tags zuweisen) erfordern Login. |

### 3.2 Benutzerprofil
| ID    | Beschreibung |
|-------|--------------|
| F-U1  | Jeder Nutzer hat ein öffentliches Profil mit Name, Motto, Über-mich-Text und Profilbild. |
| F-U2  | Nutzer können ihr eigenes Profil bearbeiten (Motto, Über-mich, Bild). |
| F-U3  | Admins können fremde Profile bearbeiten (`rolle === 'admin'`). |
| F-U4  | Im Profil werden alle Hobbys des Nutzers chronologisch (neueste zuerst) angezeigt. |
| F-U5  | Profilbilder werden in drei Varianten gespeichert: `_gross.jpg`, `_verpixelt.jpg` (Pixel-Vorschau), `_thumb.jpg`. |
| F-U6  | Profilbilder können vom Eigentümer gelöscht werden. |

### 3.3 Hobby-Verwaltung (CRUD)
| ID    | Beschreibung |
|-------|--------------|
| F-H1  | Angemeldete Nutzer können Hobbys mit Name (≥3 Zeichen), Beschreibung (≥5 Zeichen) und optionalem Bild anlegen. |
| F-H2  | Hobbys werden dem anlegenden Nutzer (`user_id`) zugeordnet. |
| F-H3  | Übersichtsseite mit Pagination (10 pro Seite), sortiert nach Erstellungsdatum. |
| F-H4  | Detailansicht eines Hobbys mit zugewiesenen Tags, Bildern und Eigentümer-Info. |
| F-H5  | Bearbeiten und Löschen nur durch Eigentümer oder Admin (über `Gate`). |
| F-H6  | Hobby-Bilder werden in drei Varianten generiert (gross, verpixelt, thumb) — Quer-/Hochformat wird automatisch erkannt. |
| F-H7  | Erlaubte Bildformate: JPG, JPEG, BMP, PNG, GIF. |
| F-H8  | Bilder können separat über eine Lösch-Route entfernt werden. |

### 3.4 Tag-Verwaltung
| ID    | Beschreibung |
|-------|--------------|
| F-T1  | Tags besitzen `name` (≥3 Zeichen) und `style` (CSS-Klassen oder Inline-Stil, ≥5 Zeichen). |
| F-T2  | Tag-Übersicht ist öffentlich einsehbar. |
| F-T3  | Tags anlegen, bearbeiten und löschen ist Admins vorbehalten. |
| F-T4  | Erfolgsmeldungen werden nach Tag-CRUD-Aktionen angezeigt. |

### 3.5 Hobby ↔ Tag Verknüpfung
| ID    | Beschreibung |
|-------|--------------|
| F-HT1 | Ein Hobby kann beliebig viele Tags besitzen (n:m über `hobby_tag`). |
| F-HT2 | Tags lassen sich an ein Hobby anhängen (`attach`) und entfernen (`detach`) — nur durch den Eigentümer. |
| F-HT3 | Hobbys können nach Tag gefiltert aufgelistet werden (`/hobby/tag/{tag_id}`), paginiert, sortiert nach `updated_at` DESC. |
| F-HT4 | In der Hobby-Detailansicht werden zugewiesene und noch verfügbare Tags getrennt angezeigt. |

### 3.6 Statische Seiten
| ID    | Beschreibung |
|-------|--------------|
| F-S1  | Startseite (`/`). |
| F-S2  | Info-Seite (`/info`). |
| F-S3  | Kontaktseite (`/contact`). |
| F-S4  | Impressum (`/impressum`). |

---

## 4. Nicht-funktionale Anforderungen

| ID    | Bereich         | Anforderung |
|-------|-----------------|-------------|
| NF-1  | Performance     | Übersichtsseiten paginiert (10/Seite); Bilder in drei Größen vorgehalten, um Bandbreite zu schonen. |
| NF-2  | Sicherheit      | CSRF-Schutz via Laravel; Passwörter gehasht; geschützte Routen via `auth`-Middleware; Autorisierung via Gates. |
| NF-3  | Wartbarkeit     | PSR-4 Autoloading; `laravel/pint` für Code-Style; `phpunit` für Tests. |
| NF-4  | Kompatibilität  | PHP ≥ 8.2, Laravel ^12.0. |
| NF-5  | Lokalisierung   | UI und Datenbankfelder auf Deutsch (`beschreibung`, `motto`, `ueber_mich`, `rolle`). |
| NF-6  | Deployment      | Lauffähig via `laravel/sail` (Docker). SQLite als Default-DB. |
| NF-7  | Dependency-Hygiene | Dependabot aktualisiert npm- und Composer-Pakete automatisch. |

---

## 5. Datenmodell

```
users
 ├─ id
 ├─ name
 ├─ email (unique)
 ├─ password (hashed)
 ├─ ueber_mich (text)
 ├─ motto
 ├─ rolle (nullable, z. B. "admin")
 ├─ email_verified_at
 └─ timestamps

hobbies
 ├─ id
 ├─ name
 ├─ beschreibung (text)
 ├─ user_id (FK → users.id)
 └─ timestamps

tags
 ├─ id
 ├─ name
 ├─ style
 └─ timestamps

hobby_tag (Pivot, n:m)
 ├─ hobby_id (FK)
 ├─ tag_id (FK)
 └─ timestamps
```

**Beziehungen:**
- `User hasMany Hobby`
- `Hobby belongsTo User`
- `Hobby belongsToMany Tag`
- `Tag belongsToMany Hobby`

---

## 6. Routen (Auszug)

| Methode | URL                                       | Controller / Aktion                  | Auth      |
|---------|-------------------------------------------|--------------------------------------|-----------|
| GET     | `/`                                       | Startseite                           | öffentlich|
| GET     | `/info` `/contact` `/impressum`           | Statische Views                      | öffentlich|
| —       | `Auth::routes()` (Login, Register, Reset) | `laravel/ui`                         | —         |
| GET     | `/home`                                   | `HomeController@index`               | auth      |
| Resource| `/hobby`                                  | `HobbyController` (CRUD)             | auth      |
| Resource| `/tags`                                   | `TagController` (CRUD, admin-only)   | auth+admin|
| Resource| `/user`                                   | `UserController` (show öffentlich)   | gemischt  |
| GET     | `/hobby/tag/{tag_id}`                     | `hobbyTagController@getFilteredHobbies` | öffentlich |
| GET     | `/hobby/{hobby_id}/tag/{tag_id}/attach`   | `hobbyTagController@attachTag`       | auth      |
| GET     | `/hobby/{hobby_id}/tag/{tag_id}/detach`   | `hobbyTagController@detachTag`       | auth      |
| GET     | `/delete-image/hobby/{hobby_id}`          | `HobbyController@deleteImages`       | auth      |
| GET     | `/delete-image/user/{user_id}`            | `UserController@deleteImages`        | auth      |

---

## 7. Rollen & Berechtigungen

| Aktion                          | Gast | Nutzer | Eigentümer | Admin |
|---------------------------------|:----:|:------:|:----------:|:-----:|
| Hobbys / Profile / Tags ansehen | ✅   | ✅     | ✅         | ✅    |
| Registrieren / Anmelden         | ✅   | —      | —          | —     |
| Hobby anlegen                   | ❌   | ✅     | ✅         | ✅    |
| Eigenes Hobby bearbeiten/löschen| ❌   | ❌     | ✅         | ✅    |
| Fremdes Hobby bearbeiten/löschen| ❌   | ❌     | ❌         | ✅    |
| Tags an eigenes Hobby (de)attachen | ❌| ❌    | ✅         | ✅    |
| Tag erstellen / bearbeiten / löschen | ❌| ❌  | ❌         | ✅    |
| Eigenes Profil bearbeiten       | ❌   | ❌     | ✅         | ✅    |
| Fremdes Profil bearbeiten       | ❌   | ❌     | ❌         | ✅    |

---

## 8. Technologie-Stack

- **Backend:** PHP 8.2, Laravel 12
- **Auth-UI:** `laravel/ui` 4.5
- **Bildverarbeitung:** `intervention/image`
- **Frontend:** Blade, Bootstrap, Sass, Vite, Axios
- **Datenbank:** SQLite (Default) — MySQL/Postgres möglich
- **Dev/Tooling:** Laravel Sail (Docker), Pint (Code-Style), PHPUnit, Mockery, Faker
- **CI / Maintenance:** Dependabot (npm + Composer), GitHub Actions
- **Lizenz:** MIT

---

## 9. Bekannte Schwächen / Tech-Debt

Aus dem Code ablesbare Stellen, die in einer nächsten Iteration adressiert werden sollten:

| ID  | Beobachtung |
|-----|-------------|
| TD1 | `TagController::__construct` registriert eine nicht existierende Middleware `'admin php'` — vermutlich Tippfehler für `'admin'`. |
| TD2 | `HobbyController::store` führt `Hobby::create` **und** `update` **und** `save` aus — überflüssige DB-Aufrufe. |
| TD3 | `HobbyController::store` setzt `user_id` lokal in `$hobby`, übergibt aber `$validated` an `Hobby::create` — `user_id` landet nicht in der DB. |
| TD4 | `UserController::deleteImages` nutzt `fileExists` (PHPUnit-Helper) statt `file_exists`; `else if`-Bedingungen vergleichen Strings statt Existenz; im letzten Branch wird ein falscher Pfad (`/img/hobby/`) verwendet. |
| TD5 | Lösch-Routen für Bilder sind GET statt DELETE — anfällig für CSRF-ähnliche Probleme über bloße Links. |
| TD6 | `attach`/`detach`-Routen sind GET, sollten POST/DELETE sein. |
| TD7 | `routes/web.php` registriert `Auth::routes()` und die Home-Route doppelt. |
| TD8 | `intervention/image` Facade-API (v2) — bei Upgrade auf v3 ist `Image::make()` durch den `ImageManager` zu ersetzen. |
| TD9 | Keine Tests für Hobby-/Tag-/User-Flows vorhanden. |
| TD10| Validierung erlaubt MIME-Typ `bmp` mit Leerzeichen-Bug: `'mimes: jpg,...'` (Leerzeichen nach Doppelpunkt). |

---

## 10. Roadmap-Vorschlag

**v1.1 — Code-Qualität**
- Tippfehler-Middleware entfernen, Routen-Dopplungen bereinigen.
- `Hobby::store` aufräumen und `user_id` korrekt persistieren.
- Bild-Lösch-/Tag-Attach-Routen auf POST/DELETE umstellen mit CSRF-Token.
- Feature-Tests für Hobby- und Tag-CRUD ergänzen.

**v1.2 — UX-Verbesserungen**
- Tag-Filter mit Mehrfachauswahl.
- Volltextsuche über Hobby-Name und -Beschreibung.
- Markdown-Unterstützung in Beschreibungen.

**v1.3 — Erweiterte Features**
- Kommentare oder Likes auf Hobbys.
- Followen anderer Nutzer.
- API-Endpunkte (z. B. via Laravel Sanctum) für mobile Clients.
- Mehrsprachigkeit (DE/EN).

---

## 11. Akzeptanzkriterien (Auszug)

- Ein Gast kann sich registrieren, anmelden und ein Hobby mit Bild anlegen.
- Das angelegte Hobby erscheint in der Hobby-Übersicht und im eigenen Profil.
- Ein Admin kann ein neues Tag erstellen, das Tag erscheint in der Tag-Übersicht.
- Der Eigentümer kann das Tag an sein Hobby anhängen und wieder entfernen.
- Ein zweiter Nutzer sieht das Hobby öffentlich, kann es aber nicht bearbeiten (HTTP 403).
- Bilder werden in drei Größen unter `public/img/hobby/{id}_*.jpg` abgelegt.
- Ein Bild kann durch den Eigentümer gelöscht werden.

---

## 12. Out of Scope (v1.0)

- Native Mobile App
- Bezahlfunktionen / Premium-Konten
- Echtzeit-Benachrichtigungen / Broadcasting
- Soziale Login-Provider (Google, GitHub …)
- Mehrsprachige UI

---

*Ende des Dokuments.*
