# CarpoolingTime

A carpooling web app (BlaBlaCar-style) written in plain PHP with a hand-made MVC architecture and MySQL. Drivers publish trips, passengers book them, and money moves between accounts when a trip is closed.

Built for the LO07 web course at the Université de Technologie de Troyes (UTT).

## Features

| Role | What they can do |
|---|---|
| **Administrator** | List and add users (drivers, passengers), vehicles and cities |
| **Driver** (`conducteur`) | See their vehicles and trips, publish a trip, see who booked it, close it (passengers pay, driver gets paid) |
| **Passenger** (`passager`) | Book an active trip, see their bookings |
| **Examiner** (`examinateur`) | Inspect PHP superglobals (`$_SESSION`, `$_COOKIE`, `$_GET`, `$_SERVER`), generate 10 random bookings (admin only) |

### Virtual balance

Every user has a **real balance** (💰, money on the account) and passengers also have a **virtual balance** (⏳): the real balance minus the price of every trip they booked that isn't closed yet.

- A passenger can only book a trip if their virtual balance covers its price, so nobody can book more than they can pay.
- When a driver closes a trip, everything happens in one SQL transaction: the trip becomes `passif`, each passenger is charged, the driver is credited. If any step fails, nothing changes.

## Try it

### Option 1: Docker (works anywhere)

Needs [Docker Desktop](https://www.docker.com/products/docker-desktop/).

```bash
git clone https://github.com/Sunlessx/CarpoolingTime.git
cd CarpoolingTime
docker compose up -d
```

| URL | What |
|---|---|
| http://localhost:8080 | The app |
| http://localhost:8081 | phpMyAdmin, to look at the database |

The database is created and filled from `blablacar2026.sql` on first start. To reset it to the original data:

```bash
docker compose down -v && docker compose up -d
```

### Option 2: XAMPP / WAMP / MAMP

1. Copy the project folder into `htdocs/` (XAMPP) or `www/` (WAMP).
2. In phpMyAdmin, create a database named `blablacar` and import `blablacar2026.sql`.
3. Open http://localhost/CarpoolingTime/

The default connection is `root` with an empty password on `localhost`, which matches XAMPP and WAMP out of the box.

### Option 3: your own server (e.g. a university server)

Copy `app/controller/config.local.example.php` to `app/controller/config.local.php` and put your database name, user and password in it. That file is ignored by git, so your password never ends up on GitHub.

## Test accounts

All passwords are `secret`.

| Role | Logins |
|---|---|
| Administrator | `boss` |
| Driver | `trisprior`, `foureaton`, `jeaninematthews`, `marclem` |
| Passenger | `calebprior`, `christinanobody`, `peterhayes`, `ericcoulter`, `toriwu`, ... |

A quick tour:

1. Log in as `calebprior` → **Passager → Réserver un trajet** → pick a trip. Watch ⏳ go down while 💰 stays the same.
2. Log out, log in as `jeaninematthews` (the driver of trips 1, 3 and 5) → **Conducteur → Voir passagers**, then **Clôturer un trajet**.
3. Log back in as `calebprior`: 💰 has now gone down too.

## How it's built

```
index.php                     entry point: resets the session, redirects to the router
app/
├── router/router.php         reads ?action=... and calls the matching controller method
├── controller/               one class per entity, checks the user's role, then calls models and views
│   └── config.php            database connection settings
├── model/                    one class per table; Model.php holds the single PDO connection
└── view/                     HTML + PHP templates, Bootstrap 5
    └── fragment/             header, menu, footer shared by every page
blablacar2026.sql             tables + test data
```

Every request goes `index.php → router.php?action=X → ControllerY::method() → ModelZ → view`.

The database has 5 tables: `utilisateur`, `vehicule`, `ville`, `trajet`, `reservation`.

A full line-by-line walkthrough of the code is in [docs/CODE_EXPLAINED.md](docs/CODE_EXPLAINED.md).

## Tech

PHP 8 (works on 7.4+), MySQL 8 / MariaDB, PDO with prepared statements, PHP sessions, Bootstrap 5.

## Known limitations

This is a course project, not production code:

- Passwords are stored in plain text, and forms use `GET`, so the password appears in the URL.
- A driver can close any trip if they type its id in the URL, not only their own.
- New ids are computed with `MAX(id) + 1`, which can collide if two people insert at the exact same time.
- `ModelNotation.php` (driver ratings) is a draft: the `notation` table doesn't exist in the SQL file and nothing uses it yet.
