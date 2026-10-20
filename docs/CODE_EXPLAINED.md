# CarpoolingTime: the code explained line by line

This file walks through every file of the project in the order a request travels through it: entry point, router, configuration, models, controllers, then views. Line numbers match the files in the repository.

## Contents

1. [The big picture](#1-the-big-picture)
2. [index.php](#2-indexphp)
3. [app/router/router.php](#3-approuterrouterphp)
4. [app/controller/config.php](#4-appcontrollerconfigphp)
5. [app/model/Model.php](#5-appmodelmodelphp)
6. [The model classes](#6-the-model-classes)
7. [The controller classes](#7-the-controller-classes)
8. [The views](#8-the-views)
9. [blablacar2026.sql](#9-blablacar2026sql)
10. [Docker files](#10-docker-files)
11. [Following one request from start to finish](#11-following-one-request-from-start-to-finish)

---

## 1. The big picture

The app follows the **MVC** pattern (Model, View, Controller):

| Layer | Folder | Job |
|---|---|---|
| **Router** | `app/router/` | Reads which page was asked for (`?action=...`) and calls the right controller method |
| **Controller** | `app/controller/` | Checks who is logged in and what they're allowed to do, reads the form data, asks the models for data, then picks a view |
| **Model** | `app/model/` | Talks to the database. One class per table. Returns PHP objects |
| **View** | `app/view/` | HTML with small bits of PHP that display the data the controller prepared |

Every page of the site is the same file, `router.php`, with a different `action` parameter in the URL:

```
router.php?action=login
router.php?action=trajetCreate
router.php?action=reservationCreated&trajet_id=3
```

**Who is logged in** is stored in the PHP session (`$_SESSION`), a small set of values the server keeps for each browser, linked to it by a cookie:

| Key | Meaning |
|---|---|
| `login_id` | Id of the logged-in user, or `-1` if nobody is logged in |
| `login_role` | `administrateur`, `conducteur` or `passager` |
| `login_nom`, `login_prenom` | Last name and first name, shown in the menu |
| `login_solde` | Real balance at login time, shown in the menu |

---

## 2. index.php

```php
1  <?php
2  session_start();
3  $_SESSION['login_id'] = -1;
4  header('Location: app/router/router.php?action=accueil');
5  ?>
```

- **Line 1**: opens a PHP block. Everything until `?>` is PHP code, not HTML.
- **Line 2**: `session_start()` creates a session for this browser, or resumes the existing one. It must run before reading or writing `$_SESSION`.
- **Line 3**: sets `login_id` to `-1`, meaning "nobody is logged in". Opening the site's root URL therefore always logs you out.
- **Line 4**: `header('Location: ...')` sends an HTTP redirect. The browser immediately loads the home page through the router. The path is relative, so this works whatever folder the project is in.
- **Line 5**: closes the PHP block.

---

## 3. app/router/router.php

The router is the single entry point for every page.

```php
1  <?php
2  session_start();
3  // si on arrive directement ici sans passer par index.php, on est "non connecté"
4  $_SESSION['login_id'] = $_SESSION['login_id'] ?? -1;
```

- **Line 2**: resumes the session, so the router knows who is logged in.
- **Lines 3–4**: if someone opens `router.php` directly (from a bookmark, for example) without going through `index.php`, `login_id` doesn't exist yet. The `??` operator ("null coalescing") means "use the left value if it exists, otherwise the right one". So an existing `login_id` is kept, and a missing one becomes `-1`. Without this, every controller would read an undefined key and PHP would print warnings.

```php
6  require_once '../controller/ControllerAccueil.php';
7  require_once '../controller/ControllerUtilisateur.php';
8  require_once '../controller/ControllerVehicule.php';
9  require_once '../controller/ControllerVille.php';
10 require_once '../controller/ControllerTrajet.php';
11 require_once '../controller/ControllerReservation.php';
```

- **Lines 6–11**: load the six controller classes. `require_once` stops with an error if a file is missing (`include` would only warn), and loads each file at most once even if it's required again elsewhere. The paths are relative to the folder of the script being run, `app/router/`, so `../controller/` means `app/controller/`. This is why every other relative path in the project (`../model/`, `../view/`) also starts from `app/router/`.

```php
13 $args = $_GET ?? [];
14 $action = $args['action'] ?? 'accueil';
```

- **Line 13**: `$_GET` holds the parameters of the URL (everything after `?`). For `router.php?action=login`, `$_GET` is `['action' => 'login']`.
- **Line 14**: reads `action`. If the URL has none, the home page (`accueil`) is shown.

```php
16 switch ($action) {
17     case 'accueil':
18         ControllerAccueil::accueil();
19         break;
...
98     default:
99         echo "<h2>Erreur : action '$action' non reconnue</h2>";
100        break;
101 }
```

- **Line 16**: `switch` compares `$action` with each `case` in turn.
- **Lines 17–19**: if it's `accueil`, call the static method `accueil()` of `ControllerAccueil`. `::` calls a **static** method: a method that belongs to the class itself, with no object needed. `break` leaves the `switch`; without it, PHP would continue into the next case.
- **Lines 20–97**: the same pattern for every action. The full list:

| Action | Controller method | Page |
|---|---|---|
| `accueil` | `ControllerAccueil::accueil` | Home page |
| `login` | `ControllerUtilisateur::login` | Login form |
| `logined` | `ControllerUtilisateur::logined` | Checks the login form |
| `logout` | `ControllerUtilisateur::logout` | Logs out |
| `utilisateurReadAll` | `ControllerUtilisateur::readAll` | All users (admin) |
| `utilisateurCreateConducteur` / `...CreatedConducteur` | `createConducteur` / `createdConducteur` | Form / save a new driver (admin) |
| `utilisateurCreatePassager` / `...CreatedPassager` | `createPassager` / `createdPassager` | Form / save a new passenger (admin) |
| `vehiculeReadAll` | `ControllerVehicule::readAll` | All vehicles (admin) |
| `vehiculeCreate` / `vehiculeCreated` | `create` / `created` | Form / save a vehicle (admin) |
| `vehiculeMesVehicules` | `ControllerVehicule::mesVehicules` | My vehicles (driver) |
| `villeReadAll` | `ControllerVille::readAll` | All cities (admin) |
| `villeCreate` / `villeCreated` | `create` / `created` | Form / save a city (admin) |
| `trajetMesTrajets` | `ControllerTrajet::mesTrajets` | My trips (driver) |
| `trajetCreate` / `trajetCreated` | `create` / `created` | Form / save a trip (driver) |
| `trajetPassagers` | `ControllerTrajet::passagers` | Passengers of a trip (driver) |
| `trajetCloture` | `ControllerTrajet::cloture` | Close a trip and process payments (driver) |
| `reservationMesReservations` | `ControllerReservation::mesReservations` | My bookings (passenger) |
| `reservationCreate` / `reservationCreated` | `create` / `created` | Form / save a booking (passenger) |
| `examinateurReservations` | `ControllerReservation::reservationsAleatoires` | 10 random bookings (admin) |
| `innovations` | `ControllerAccueil::innovations` | Innovations page |
| `examinateurSuperglobales` | `ControllerAccueil::superglobales` | Superglobals page |

A naming convention runs through the app: **`xxxCreate`** shows an empty form, **`xxxCreated`** (past tense) receives the filled form and saves it.

- **Lines 98–100**: `default` runs when no `case` matched, i.e. an unknown action.

---

## 4. app/controller/config.php

Decides how to connect to the database. It's loaded by `Model.php` the first time a query runs.

```php
1  <?php
2  if (!defined('DEBUG')) {
3      define('DEBUG', FALSE);
4  }
```

- **Lines 2–4**: `define` creates a **constant**, a value that can never change afterwards. `DEBUG` controls whether connection details are printed on the page (lines 30–37). The `if (!defined(...))` lets another file define `DEBUG` as `TRUE` before this one loads, without causing an "already defined" error.

```php
6  // Valeurs par défaut : MySQL local (XAMPP / WAMP / MAMP)
7  $db_host = 'localhost';
8  $db_name = 'blablacar';
9  $db_user = 'root';
10 $db_password = '';
```

- **Lines 7–10**: the default settings, which match a fresh XAMPP or WAMP install: server on the same machine (`localhost`), database `blablacar`, user `root`, no password.

```php
12 // Fichier privé (non versionné) pour un serveur précis, ex: dev-isi.utt.fr
13 // Voir config.local.example.php
14 if (file_exists(__DIR__ . '/config.local.php')) {
15     include __DIR__ . '/config.local.php';
16 }
```

- **Line 14**: `__DIR__` is the folder of the current file (`app/controller`). If a file named `config.local.php` exists there...
- **Line 15**: ...it's loaded, and the variables it sets (`$db_host`, `$db_name`...) replace the defaults. This file is listed in `.gitignore`, so real server passwords stay on the server and never reach GitHub. `config.local.example.php` shows what to put in it.

```php
18 // Variables d'environnement (utilisées par Docker), prioritaires sur le reste
19 $db_host = getenv('DB_HOST') ?: $db_host;
20 $db_name = getenv('DB_NAME') ?: $db_name;
21 $db_user = getenv('DB_USER') ?: $db_user;
22 $db_password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : $db_password;
```

- **Lines 19–21**: `getenv('DB_HOST')` reads an **environment variable**, a setting given to the program from outside. `docker-compose.yml` sets these. `?:` (the "Elvis operator") keeps the left value if it's non-empty, otherwise the right one. So when an environment variable is set, it wins; otherwise the value from above is kept.
- **Line 22**: the password needs a stricter test. An empty password `''` is valid, but `?:` would treat it as "empty" and skip it. `getenv` returns `false` only when the variable doesn't exist at all, so this line uses the environment value whenever the variable exists, even if it's empty.

```php
24 $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
25 $username = $db_user;
26 $password = $db_password;
```

- **Line 24**: builds the **DSN** (Data Source Name), the connection string PDO expects: database type, server, database name, and character set. `utf8` makes accents (é, è, ç) display correctly. Double quotes `"..."` let PHP insert the variables' values into the text.
- **Lines 25–26**: copies the user and password into the variable names `Model.php` uses.

```php
28 $root = dirname(dirname(__DIR__)) . "/";
```

- **Line 28**: `dirname` goes up one folder. From `app/controller`, twice up is the project root. Only used by the debug display below.

```php
30 if (DEBUG) {
31     echo ("<ul>");
32     echo (" <li>dsn = $dsn</li>");
33     echo (" <li>username = $username</li>");
34     echo (" <li>password = ****</li>");
35     echo ("<li>---</li>");
36     echo (" <li>root = $root</li>");
37     echo ("</ul>");
38 }
```

- **Lines 30–37**: in debug mode, prints the connection details as an HTML list (`<ul>` = list, `<li>` = item). The password is masked, so debug mode never displays it.

---

## 5. app/model/Model.php

Creates the single connection to the database that the whole app shares.

```php
1  <?php
2  class Model extends PDO {
3      private static $_instance;
```

- **Line 2**: declares the class `Model`. `extends PDO` makes it a child of PDO, PHP's built-in database class. (In practice only the static method below is used.)
- **Line 3**: a `private static` property: one value shared by the whole class (`static`), only readable from inside it (`private`). It will hold the connection.

```php
5      public function __construct() {
6      }
```

- **Lines 5–6**: an empty constructor. It replaces PDO's constructor so that `new Model()` doesn't try to connect. The class is meant to be used only through `getInstance()`.

```php
8      public static function getInstance() {
9          include_once '../controller/config.php';
10         if (DEBUG) echo ("Model : getInstance : dsn = $dsn</br>");
11         $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
```

- **Line 8**: the method every model calls to get the connection.
- **Line 9**: loads the settings (`$dsn`, `$username`, `$password`). The path is relative to `app/router/`, where the running script lives.
- **Line 10**: in debug mode, shows which database is used.
- **Line 11**: tells PDO to **throw an exception** on any SQL error instead of failing silently. That's what lets the `try / catch` blocks in the models (and the transaction in `ControllerTrajet::cloture`) notice errors.

```php
12         if (!isset(self::$_instance)) {
13             try {
14                 self::$_instance = new PDO($dsn, $username, $password, $options);
15             } catch (PDOException $e) {
16                 printf("%s - %s<p/>\n", $e->getCode(), $e->getMessage());
17             }
18         }
19         return self::$_instance;
20     }
21 }
```

- **Line 12**: `self::$_instance` is the shared property. If no connection exists yet...
- **Line 14**: ...open one. `new PDO(...)` actually connects to MySQL.
- **Lines 15–16**: if it fails (wrong password, server down), print the error code and message.
- **Line 19**: return the connection. The next call skips line 14 and returns the same one.

This is the **Singleton** pattern: however many queries a page runs, there is only ever one connection, which saves time and keeps the transaction in `cloture()` working (all its queries must go through the same connection).

---

## 6. The model classes

All six models (`ModelUtilisateur`, `ModelVehicule`, `ModelVille`, `ModelTrajet`, `ModelReservation`, `ModelNotation`) share one structure. `ModelUtilisateur` is explained in full first; the others are described by what differs.

### 6.1 ModelUtilisateur.php

```php
1  <?php
2  require_once 'Model.php';
3
4  class ModelUtilisateur {
5      private $id, $nom, $prenom, $role, $login, $password, $solde;
```

- **Line 2**: loads `Model.php` (same folder, so no `../`). `require_once` also prevents loading it twice when several models are used on one page.
- **Line 5**: the object's properties, one per column of the `utilisateur` table: id, last name, first name, role, login, password, balance. `private` means they can't be changed from outside the class.

```php
7      public function __construct($id = NULL, $nom = NULL, $prenom = NULL, $role = NULL, $login = NULL, $password = NULL, $solde = NULL) {
8          if (!is_null($id)) {
9              $this->id = $id;
...
15             $this->solde = $solde;
16         }
17     }
```

- **Line 7**: the constructor, called when an object is created. Every parameter defaults to `NULL`, so it can be called with no arguments.
- **Lines 8–16**: fill the properties only when an id is given. This matters for `PDO::FETCH_CLASS` (used below): PDO first fills the properties straight from the database row, then calls the constructor with no arguments. Without the `if`, the constructor would overwrite every value with `NULL`.

```php
19     public static function getAll() {
20         try {
21             $pdo = Model::getInstance();
22             $query = $pdo->query("SELECT * FROM utilisateur");
23             return $query->fetchAll(PDO::FETCH_CLASS, "ModelUtilisateur");
24         } catch (PDOException $e) {
25             die($e->getMessage());
26         }
27     }
```

- **Line 21**: gets the shared connection.
- **Line 22**: `query()` runs SQL that has no user input in it. `SELECT *` = every column, every row.
- **Line 23**: `fetchAll` reads all the rows. `PDO::FETCH_CLASS, "ModelUtilisateur"` turns each row into a `ModelUtilisateur` object instead of a plain array, so views can write `$u->nom`.
- **Lines 24–25**: if the query fails, `die()` stops the script and prints the error.

```php
29     public static function getOne($id) {
30         try {
31             $pdo = Model::getInstance();
32             $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = :id");
33             $stmt->execute(['id' => $id]);
34             $stmt->setFetchMode(PDO::FETCH_CLASS, 'ModelUtilisateur');
35             return $stmt->fetch();
...
```

- **Line 32**: `prepare` sends the SQL with a **placeholder** `:id` instead of the real value. This is a **prepared statement**.
- **Line 33**: `execute` sends the value separately. MySQL treats it strictly as data, never as SQL code, which blocks **SQL injection** (someone typing SQL into a form to tamper with the database). Every query in the app that involves user input works this way.
- **Line 34**: same as `FETCH_CLASS` above, set for the single `fetch()` call.
- **Line 35**: `fetch()` returns one object, or `false` if no row matched. Callers test `if ($user)` for that reason.

```php
41     public static function getByLogin($login) {
...
44             $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE login = :login");
```

- **Lines 41–51**: the same as `getOne`, but finds the user by login. Used to check the login form.

```php
53     public static function insert($nom, $prenom, $role, $login, $password, $solde = 0) {
54         try {
55             $pdo = Model::getInstance();
56             $stmtMax = $pdo->query("SELECT MAX(id) AS maxid FROM utilisateur");
57             $row = $stmtMax->fetch(PDO::FETCH_ASSOC);
58             $newid = $row['maxid'] + 1;
59             $stmt = $pdo->prepare("INSERT INTO utilisateur (id, nom, prenom, role, login, password, solde) VALUES (:newid, :nom, :prenom, :role, :login, :password, :solde)");
60             $stmt->execute(['newid' => $newid, 'nom' => $nom, ...]);
61             return $newid;
```

- **Line 53**: adds a user. `$solde = 0` is a default value, used if the caller passes no balance.
- **Lines 56–58**: the table has no `AUTO_INCREMENT`, so the new id is computed by hand: highest existing id + 1. `FETCH_ASSOC` returns the row as an array whose keys are column names (`$row['maxid']`). `AS maxid` names the result column.
- **Lines 59–60**: inserts the row with a prepared statement.
- **Line 61**: returns the new id, which the "created" page displays.

```php
67     public static function updateSolde($id, $solde) {
...
70             $stmt = $pdo->prepare("UPDATE utilisateur SET solde = :solde WHERE id = :id");
```

- **Lines 67–76**: replaces a user's balance with a new value. Used when a trip is closed. Line 72 returns what `execute()` returned: `true` if the update worked.

```php
78     public static function getVirtualSolde($id) {
79         try {
80             $user = self::getOne($id);
81             if (!$user) return 0;
82
83             $pdo = Model::getInstance();
84             // Somme des prix des trajets actifs réservés par l'utilisateur
85             $sql = "SELECT SUM(t.prix) FROM reservation r
86                     JOIN trajet t ON r.trajet_id = t.id
87                     WHERE r.passager_id = :id AND t.statut = 'actif'";
88             $stmt = $pdo->prepare($sql);
89             $stmt->execute(['id' => $id]);
90             $engagements = $stmt->fetchColumn();
91
92             return $user->solde - ($engagements ?: 0);
93         } catch (PDOException $e) {
94             return 0;
95         }
96     }
```

The **virtual balance**, the main idea of the project.

- **Line 80**: `self::` calls another static method of the same class. Gets the user.
- **Line 81**: unknown user → 0.
- **Lines 85–87**: adds up (`SUM`) the price of every trip this passenger has booked that is still `actif` (not closed yet, so not paid yet). `JOIN` links each reservation to its trip; `r` and `t` are short aliases for the two tables.
- **Line 90**: `fetchColumn()` returns the single value of the result: the sum, or `NULL` if there are no bookings.
- **Line 92**: real balance minus money already promised. `?: 0` turns `NULL` into 0.
- **Lines 93–94**: on error, return 0 rather than stopping the page, since this is called from the menu on every page.

```php
98     public function __get($prop) {
99         return $this->$prop;
100    }
```

- **Lines 98–100**: `__get` is a **magic method**. PHP calls it automatically when code outside the class reads a property it can't access. The properties are `private`, so `$user->nom` from a view lands here, and `$this->$prop` returns the property whose name is in `$prop`. This makes the properties readable but not writable from outside.

```php
102    public function getSolde() {
103        return $this->solde;
104    }
105
106    public function getId() {
107        return $this->id;
108    }
```

- **Lines 102–108**: **getters**, ordinary methods that return one property. `$user->getSolde()` gives the same result as `$user->solde` (through `__get`), but says explicitly what is being read. The trip-closing code and the menu use `getSolde()`.

### 6.2 ModelVehicule.php

Same structure, for the `vehicule` table.

- **Line 5**: properties `id`, `marque` (brand), `modele` (model), `annee` (year), `immatriculation` (licence plate), `proprietaire_id` (owner's user id).
- **Lines 18–26, `getAll()`**: every vehicle.
- **Lines 28–37, `getByProprietaire($proprietaire_id)`**: the vehicles owned by one driver (`WHERE proprietaire_id = :proprietaire_id`). Used for "Mes véhicules" and for the vehicle list in the trip form.
- **Lines 39–49, `getOne($id)`**: one vehicle by id.
- **Lines 51–63, `insert(...)`**: adds a vehicle, with the same `MAX(id) + 1` id logic.
- **Lines 65–67**: `__get`, as above.

### 6.3 ModelVille.php

The `ville` (city) table: just `id` and `nom`.

- **Line 17**: `getAll()` sorts with `ORDER BY nom`, so the city dropdowns are alphabetical.
- **Lines 24–34, `getOne`** and **36–48, `insert`**: same as the other models.

### 6.4 ModelTrajet.php

The `trajet` (trip) table.

- **Line 5**: `id`, `ville_depart` and `ville_arrivee` (city ids), `conducteur_id` (driver), `vehicule_id`, `prix` (price per passenger), `date_depart`, `heure_depart` (date and time), `statut` (`actif` = open for booking, `passif` = closed and paid).
- **Lines 21–30, `getByConducteur`**: a driver's trips, newest first (`ORDER BY date_depart DESC`).
- **Lines 32–40, `getActifs`**: every trip still open, for the booking form.
- **Lines 42–52, `getOne`**: one trip.

```php
54     public static function insert($ville_depart, $ville_arrivee, $conducteur_id, $vehicule_id, $prix, $date_depart, $heure_depart) {
55         try {
56             $pdo = Model::getInstance();
57             // Anti-double insertion check
58             $stmtCheck = $pdo->prepare("SELECT id FROM trajet WHERE ville_depart = :vd AND ville_arrivee = :va AND conducteur_id = :cid AND date_depart = :dd AND heure_depart = :hd");
59             $stmtCheck->execute([...]);
60             $existing = $stmtCheck->fetch();
61             if ($existing) return $existing['id'];
```

- **Lines 57–61**: protection against duplicates. Because forms use `GET`, refreshing the "trip created" page would resend the form and create the same trip again. So before inserting, it looks for an identical trip (same cities, driver, date and time); if one exists, it returns that id instead of inserting.

```php
63             $stmtMax = $pdo->query("SELECT MAX(id) AS maxid FROM trajet");
64             $row = $stmtMax->fetch(PDO::FETCH_ASSOC);
65             $newid = ($row['maxid'] ?? 0) + 1;
66             $stmt = $pdo->prepare("INSERT INTO trajet (...) VALUES (..., 'actif')");
```

- **Line 65**: same id logic, with `?? 0` in case the table is empty (`MAX` then returns `NULL`).
- **Line 66**: every new trip starts as `'actif'`.
- **Lines 74–83, `updateStatut($id, $statut)`**: changes a trip's status. Used to close it.

### 6.5 ModelReservation.php

The `reservation` (booking) table: `id`, `trajet_id`, `passager_id`. A booking links one passenger to one trip.

- **Lines 15–24, `getByPassager`**: a passenger's bookings, newest first.
- **Lines 26–35, `getByTrajet`**: every booking on a trip, i.e. its passenger list.
- **Lines 37–46, `countByTrajet`**: counts the bookings on a trip (`COUNT(*)`). Not currently called anywhere.
- **Lines 48–58, `getOne`**: one booking.
- **Lines 60–78, `insert`**: like `ModelTrajet::insert`, it first checks (lines 63–67) whether this passenger already booked this trip and returns the existing id if so. A passenger can't book the same trip twice, and the random-booking generator can't create duplicates either.

### 6.6 ModelNotation.php

A draft for driver ratings: `trajet_id`, `passager_id`, `conducteur_id`, `note` (score) and `commentaire`.

- **`getByConducteur`**: a driver's ratings.
- **`getAverageByConducteur`** (lines 29–39): average score with `AVG(note)`, rounded to 2 decimals with `round(..., 2)`.
- **`insert`**: adds a rating.

The `notation` table doesn't exist in `blablacar2026.sql` and no controller uses this class, so it has no effect on the app yet. Adding the table and a "rate this trip" page would complete it.

---

## 7. The controller classes

Every controller method follows the same steps:

1. **Check access**: is someone logged in, with the right role? If not, show "Accès refusé" and stop.
2. **Read the input** from `$_GET` and pass it through `htmlspecialchars`.
3. **Call the models** to read or write data.
4. **Load a view** with `require`. The view can use every variable the method created, because `require` runs the view's code inside the method.

The access check appears at the top of almost every method:

```php
if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'administrateur') {
    echo '<h3>Accès refusé</h3>';
    return;
}
```

- `||` means "or": refuse if nobody is logged in, **or** if the role isn't the one required.
- `!==` is a strict comparison (value and type must both match).
- `return` ends the method early, so nothing below runs.

`htmlspecialchars` turns characters like `<` and `>` into `&lt;` and `&gt;`. If someone types `<script>` into a form, it's stored and shown as plain text instead of running as code in other users' browsers. This blocks **XSS** (cross-site scripting).

### 7.1 ControllerAccueil.php

```php
2  require_once '../model/ModelUtilisateur.php';
3  require_once '../model/ModelVille.php';
5  class ControllerAccueil {
6      public static function accueil() {
7          require '../view/accueil/viewAccueil.php';
8      }
10     public static function innovations() {
11         require '../view/innovations/viewInnovations.php';
12     }
14     public static function superglobales() {
15         require '../view/examinateur/viewSuperglobales.php';
16     }
```

- **Lines 2–3**: loads models. `ModelUtilisateur` is needed by the menu, which calls `getVirtualSolde` for passengers.
- **Lines 6–16**: three pages that only display a view, with no access check: anyone can see the home page, the innovations page and the superglobals page.

### 7.2 ControllerUtilisateur.php

```php
5      public static function login() {
6          require '../view/utilisateur/viewLogin.php';
7      }
```

- **Lines 5–7**: shows the login form.

```php
9      public static function logined() {
10         $login = htmlspecialchars($_GET['login'] ?? '');
11         $password = htmlspecialchars($_GET['password'] ?? '');
12
13         $user = ModelUtilisateur::getByLogin($login);
14         if ($user && $user->password === $password) {
15             $_SESSION['login_id'] = $user->id;
16             $_SESSION['login_role'] = $user->role;
17             $_SESSION['login_nom'] = $user->nom;
18             $_SESSION['login_prenom'] = $user->prenom;
19             $_SESSION['login_solde'] = $user->solde;
20             header('Location: router.php?action=accueil');
21         } else {
22             echo '<h3>Identifiants incorrects</h3>';
23             echo '<a href="router.php?action=login">Retour</a>';
24         }
25     }
```

- **Lines 10–11**: read what was typed in the form, or `''` if a field is missing.
- **Line 13**: look the user up by login.
- **Line 14**: login succeeds only if the user exists (`$user` isn't `false`) **and** (`&&`) the password matches exactly (`===`).
- **Lines 15–19**: store who is logged in in the session. From now on, every page knows the user.
- **Line 20**: redirect to the home page.
- **Lines 22–23**: wrong login or password: an error message and a link back.

```php
27     public static function logout() {
28         $_SESSION['login_id'] = -1;
29         session_destroy();
30         header('Location: router.php?action=accueil');
31     }
```

- **Line 28**: mark as logged out.
- **Line 29**: `session_destroy()` deletes the whole session on the server.
- **Line 30**: back to the home page.

```php
33     public static function readAll() {
34         if (... !== 'administrateur') { ... }
38         $utilisateurs = ModelUtilisateur::getAll();
39         require '../view/utilisateur/viewAll.php';
40     }
```

- **Lines 33–40**: admin only. Loads every user into `$utilisateurs`; the view loops over that variable to draw the table.

```php
50     public static function createdConducteur() {
...
55         $nom = htmlspecialchars($_GET['nom']);
56         $prenom = htmlspecialchars($_GET['prenom']);
57         $login = htmlspecialchars($_GET['login']);
58         $password = htmlspecialchars($_GET['password']);
59         $solde = htmlspecialchars($_GET['solde'] ?? 100);
60         $id = ModelUtilisateur::insert($nom, $prenom, 'conducteur', $login, $password, $solde);
61         require '../view/utilisateur/viewInserted.php';
62     }
```

- **Lines 42–48, `createConducteur()`**: admin only, shows the empty form.
- **Lines 55–59**: read the form fields. The starting balance defaults to 100.
- **Line 60**: create the user with the role fixed to `'conducteur'`. Fixing the role here means the form can't be tampered with to create an admin.
- **Line 61**: the confirmation page shows `$id`.
- **Lines 64–84, `createPassager()` and `createdPassager()`**: identical, with the role `'passager'`.

### 7.3 ControllerVehicule.php

- **Lines 6–13, `readAll()`**: admin only, all vehicles.

```php
15     public static function create() {
...
20         $utilisateurs = ModelUtilisateur::getAll();
21         $conducteurs = array_filter($utilisateurs, function($u) { return $u->role === 'conducteur'; });
22         require '../view/vehicule/viewInsert.php';
23     }
```

- **Line 20**: every user.
- **Line 21**: `array_filter` keeps only the items for which the given function returns `true`. The function (an **anonymous function**, written inline) keeps users whose role is `conducteur`. Only drivers can own a vehicle, so only drivers appear in the "owner" dropdown.
- **Lines 25–37, `created()`**: reads the form, inserts the vehicle, shows the confirmation.

```php
39     public static function mesVehicules() {
40         if ($_SESSION['login_id'] == -1 || $_SESSION['login_role'] !== 'conducteur') { ... }
44         $vehicules = ModelVehicule::getByProprietaire($_SESSION['login_id']);
```

- **Lines 39–46**: drivers only. Line 44 uses the logged-in user's id from the session, so a driver can only ever see their own vehicles.

### 7.4 ControllerVille.php

Admin only. `readAll()` (lines 5–12) lists cities, `create()` (14–20) shows the form, `created()` (22–30) saves a new city.

### 7.5 ControllerTrajet.php

Every method here is for drivers only.

- **Lines 9–16, `mesTrajets()`**: the logged-in driver's trips.
- **Lines 18–26, `create()`**: loads every city (`$villes`) and the driver's own vehicles (`$vehicules`) for the dropdowns of the trip form.
- **Lines 28–41, `created()`**: reads the form and creates the trip. The driver is always `$_SESSION['login_id']` (line 39), never a form field, so nobody can create a trip in someone else's name.

```php
43     public static function passagers() {
...
48         $trajet_id = htmlspecialchars($_GET['trajet_id'] ?? '');
49         if (!$trajet_id) {
50             $trajets = ModelTrajet::getByConducteur($_SESSION['login_id']);
51             require '../view/trajet/viewSelectTrajetPassagers.php';
52             return;
53         }
54         $trajet = ModelTrajet::getOne($trajet_id);
55         $reservations = ModelReservation::getByTrajet($trajet_id);
56         require '../view/trajet/viewPassagers.php';
57     }
```

One action, two steps:

- **Lines 49–53**: no trip chosen yet → show a form to pick one of the driver's trips. Submitting it calls the same action again, now with `trajet_id`.
- **Lines 54–56**: trip chosen → load it and its bookings, and show the passenger list.

```php
59     public static function cloture() {
...
64         $trajet_id = htmlspecialchars($_GET['trajet_id'] ?? '');
65         if (!$trajet_id) { ... viewSelectTrajetCloture.php ... }
70         $trajet = ModelTrajet::getOne($trajet_id);
71         if ($trajet->statut === 'passif') {
72             echo '<h3>Ce trajet est déjà clôturé</h3>';
73             return;
74         }
```

Closing a trip: the passengers pay the driver.

- **Lines 64–69**: the same two-step pattern: pick a trip first.
- **Lines 71–74**: a trip that's already closed can't be closed (and paid) twice.

```php
76         // tout dans une transaction : soit tous les paiements passent, soit aucun
77         $pdo = Model::getInstance();
78         try {
79             $pdo->beginTransaction();
```

- **Line 77**: gets the shared connection. Every model uses this same connection (see `Model.php`), so all their queries take part in the transaction.
- **Line 79**: starts a **transaction**. Every database change from now on is temporary until `commit()`. If anything fails, `rollBack()` cancels all of them. Money can never be taken from passengers without the driver being credited, or the other way round.

```php
81             // 1. Marquer le trajet comme passif
82             ModelTrajet::updateStatut($trajet_id, 'passif');
83
84             // 2. Récupérer les réservations
85             $reservations = ModelReservation::getByTrajet($trajet_id);
86             $count_paid = 0;
87
88             foreach ($reservations as $res) {
89                 $passager = ModelUtilisateur::getOne($res->passager_id);
90                 if ($passager) {
91                     // Déduire l'argent du passager
92                     $current_solde = $passager->getSolde();
93                     $prix_trajet = $trajet->prix;
94                     $nouveau_solde_passager = $current_solde - $prix_trajet;
95                     ModelUtilisateur::updateSolde($res->passager_id, $nouveau_solde_passager);
96                     $count_paid++;
97                 }
98             }
```

- **Line 82**: the trip becomes `passif`. It disappears from the booking list, and it no longer counts in passengers' virtual balance, which is right since they're about to actually pay.
- **Line 85**: every booking on the trip.
- **Lines 88–98**: for each booking, read the passenger's current balance (line 92), subtract the trip's price (94) and save it (95). `$count_paid` counts the passengers who paid; `++` adds 1.

```php
100            // 3. Créditer le conducteur
101            $conducteur = ModelUtilisateur::getOne($_SESSION['login_id']);
102            if ($conducteur) {
103                $current_solde_cond = $conducteur->getSolde();
104                $total_paye = $count_paid * $trajet->prix;
105                $nouveau_solde_conducteur = $current_solde_cond + $total_paye;
106                ModelUtilisateur::updateSolde($_SESSION['login_id'], $nouveau_solde_conducteur);
107            }
108
109            $pdo->commit();
```

- **Lines 103–106**: the driver receives number of passengers × price.
- **Line 109**: `commit()` makes every change permanent at once.

```php
110        } catch (Exception $e) {
111            if ($pdo->inTransaction()) $pdo->rollBack();
112            die("Erreur lors de la clôture : " . $e->getMessage());
113        }
114
115        // Mettre à jour la session du conducteur connecté (seulement si tout est validé)
116        if (isset($nouveau_solde_conducteur)) {
117            $_SESSION['login_solde'] = $nouveau_solde_conducteur;
118        }
119
120        require '../view/trajet/viewClotured.php';
```

- **Lines 110–112**: if any query threw an exception, cancel the whole transaction and show the error. `.` joins two strings.
- **Lines 116–118**: update the balance stored in the driver's session, only once the payments are committed. `isset` checks that the variable exists (it doesn't if the driver wasn't found). The menu also reloads the balance from the database on every page (see 8.3).
- **Line 120**: confirmation page.

### 7.6 ControllerReservation.php

- **Lines 8–15, `mesReservations()`**: passengers only, their bookings.
- **Lines 17–24, `create()`**: the booking form, listing every active trip.

```php
26     public static function created() {
...
31         $trajet_id = htmlspecialchars($_GET['trajet_id']);
32         $trajet = ModelTrajet::getOne($trajet_id);
33
34         $virtual_solde = ModelUtilisateur::getVirtualSolde($_SESSION['login_id']);
35
36         if ($virtual_solde < $trajet->prix) {
37             echo '<div class="container mt-5"><div class="alert alert-danger"><h3>Solde insuffisant</h3>';
38             echo '<p>Votre solde virtuel ('.number_format($virtual_solde, 2).'€) ne permet pas de réserver ce trajet ('.number_format($trajet->prix, 2).'€).</p>';
39             echo '<a href="router.php?action=reservationCreate" class="btn btn-primary">Retour</a></div></div>';
40             return;
41         }
42
43         $id = ModelReservation::insert($trajet_id, $_SESSION['login_id']);
44         require '../view/reservation/viewInserted.php';
45     }
```

- **Line 34**: the passenger's virtual balance (real balance minus trips already booked).
- **Lines 36–41**: if it can't cover this trip's price, refuse with a message. `number_format($x, 2)` shows a number with 2 decimals (`12.5` → `12.50`).
- **Line 43**: otherwise, book it. No money moves yet; that happens when the driver closes the trip.

```php
47     public static function reservationsAleatoires() {
...
52         $trajets = ModelTrajet::getActifs();
53         $utilisateurs = ModelUtilisateur::getAll();
54         $passagers = array_values(array_filter($utilisateurs, function($u) { return $u->role === 'passager'; }));
55
56         $added_details = [];
57         $count = 0;
58
59         if (count($trajets) > 0 && count($passagers) > 0) {
60             for ($i = 0; $i < 10; $i++) {
61                 $trajet = $trajets[array_rand($trajets)];
62                 $passager = $passagers[array_rand($passagers)];
63
64                 $id = ModelReservation::insert($trajet->id, $passager->id);
```

A testing tool for the examiner: create 10 random bookings.

- **Line 54**: every passenger. `array_values` renumbers the filtered array 0, 1, 2…, since `array_filter` keeps the original keys and leaves gaps.
- **Line 59**: only if there are trips and passengers to pick from.
- **Line 60**: a loop that runs 10 times (`$i` = 0 to 9).
- **Lines 61–62**: `array_rand` returns a random key of the array → a random trip and a random passenger.
- **Line 64**: book it. Thanks to the duplicate check in `ModelReservation::insert`, an existing pair isn't booked twice. This tool skips the virtual-balance check on purpose, to generate test data quickly.

```php
66                 $ville_dep = ModelVille::getOne($trajet->ville_depart);
67                 $ville_arr = ModelVille::getOne($trajet->ville_arrivee);
68
69                 $added_details[] = [
70                     'res_id' => $id,
71                     'passager' => $passager->nom . " " . $passager->prenom,
72                     'trajet' => ($ville_dep ? $ville_dep->nom : "?") . " -> " . ($ville_arr ? $ville_arr->nom : "?")
73                 ];
74                 $count++;
```

- **Lines 66–73**: build a readable summary of each booking for the results table. `$array[] = ...` adds an item to the end of an array. `condition ? a : b` (the **ternary operator**) means "a if the condition is true, otherwise b": the city name if found, otherwise `?`.

---

## 8. The views

### 8.1 The shape every page shares

Every view has the same skeleton:

```php
1  <?php include __DIR__ . '/../fragment/fragmentHeader.html'; ?>
2  <body>
3      <div class="container">
4          <?php include __DIR__ . '/../fragment/fragmentMenu.php'; ?>
           ... the page's own content ...
       </div>
       <?php include __DIR__ . '/../fragment/fragmentFooter.html'; ?>
   </body>
   </html>
```

- **Line 1**: inserts the shared header (HTML start, Bootstrap, colours). `__DIR__` makes the path relative to the view's own folder, so it works from any view.
- **Line 3**: `container` is a Bootstrap class that centres the content with margins.
- **Line 4**: inserts the navigation menu.
- **The end**: the shared footer, then the closing tags.

Writing the header, menu and footer once and including them everywhere means a change to the menu only needs to be made in one file.

Inside the HTML, PHP appears in two ways:

- `<?php echo htmlspecialchars($u->nom); ?>` prints a value, made safe for HTML.
- `<?php foreach ($list as $x): ?> ... <?php endforeach; ?>` repeats the HTML between them once per item. This is PHP's "alternative syntax" (`:` and `endforeach` instead of `{ }`), which reads more clearly inside HTML. `if: ... else: ... endif;` works the same way.

### 8.2 fragment/fragmentHeader.html

- **Lines 1–2**: `<!DOCTYPE html>` declares a modern HTML page; `lang="fr"` declares it's in French.
- **Line 4**: `UTF-8` encoding, so accents display correctly.
- **Line 5**: `viewport` makes the page fit phone screens.
- **Line 6**: the browser-tab title.
- **Line 7**: loads Bootstrap's CSS from a CDN (a server that hosts common libraries). `integrity` is a fingerprint: the browser refuses the file if it was tampered with.
- **Lines 8–46**: the site's own style. **Lines 9–12** define two colour variables (orange `#FF8C42`, dark blue `#2C3E50`). The rest overrides Bootstrap's default blue with the orange: navigation bar (21–23), buttons (24–31), backgrounds (32–34), striped table rows (35–37), card headers (38–40), success messages (41–45). `!important` forces a rule over Bootstrap's.
- **Line 47**: Bootstrap's JavaScript, which makes the dropdown menus open and close.

### 8.3 fragment/fragmentMenu.php

```php
2  $login_id = $_SESSION['login_id'] ?? -1;
3  $login_role = $_SESSION['login_role'] ?? '';
4  $login_nom = $_SESSION['login_nom'] ?? '';
5  $login_prenom = $_SESSION['login_prenom'] ?? '';
6  $login_solde = $_SESSION['login_solde'] ?? 0;
```

- **Lines 2–6**: copy the session values into short variables, with a default for each one in case nobody is logged in.

```php
7  // Mise à jour du solde en temps réel depuis la base de données
8  if ($login_id != -1) {
9      $user_live = \ModelUtilisateur::getOne($login_id);
10     if ($user_live) {
11         $login_solde = $user_live->getSolde();
12         $_SESSION['login_solde'] = $login_solde; // Optionnel : synchronise la session
13     }
14 }
```

- **Lines 8–14**: the balance in the session was saved at login, so it goes stale as soon as money moves (for example, a passenger whose trip was just closed by the driver). So when someone is logged in, the menu reloads their user from the database on every page (line 9) and uses the fresh balance (line 11). Line 12 also stores it back in the session. The `\` before `ModelUtilisateur` means "the class from the global namespace"; here it works the same with or without it.

```html
16 <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #FF8C42;">
18     <a class="navbar-brand text-white fw-bold" href="router.php?action=accueil">🚗 Imad and Huy</a>
19     <button class="navbar-toggler" ... data-bs-target="#navbarNav" ...>
```

- **Line 16**: a Bootstrap navigation bar. `fixed-top` keeps it at the top while scrolling (which is why `.container` has an 80px top margin in the header). `navbar-expand-lg` shows the full menu on large screens and collapses it on small ones.
- **Line 18**: the brand link on the left, back to the home page.
- **Lines 19–21**: the "hamburger" button that opens the collapsed menu on phones. `data-bs-target="#navbarNav"` tells Bootstrap which element to open (the `<div id="navbarNav">` on line 22).

```php
24 <?php if ($login_id != -1): ?>
25     <li class="nav-item">
26         <span class="nav-link text-white"><strong><?php echo htmlspecialchars($login_nom . ' ' . $login_prenom); ?></strong></span>
...
29         <span class="nav-link text-white" title="Solde Réel">💰 <?php echo number_format($login_solde, 2); ?>€</span>
...
31     <?php if ($login_role === 'passager'):
32         $virtual = \ModelUtilisateur::getVirtualSolde($login_id);
33     ?>
35         ... ⏳ <?php echo number_format($virtual, 2); ?>€ ...
37     <?php endif; ?>
38 <?php endif; ?>
```

- **Line 24**: this block only appears when someone is logged in.
- **Line 26**: their name.
- **Line 29**: their real balance, fresh from the database (lines 8–14). `title` shows a tooltip on hover.
- **Lines 31–37**: passengers also see their virtual balance, recalculated with a database query on every page load (line 32).

- **Lines 40–55**: the **Admin** dropdown, only for administrators: links to the user, vehicle and city pages. `dropdown-divider` draws a separator line.
- **Lines 57–68**: the **Conducteur** dropdown, only for drivers.
- **Lines 70–78**: the **Passager** dropdown, only for passengers.
- **Lines 80–85**: **Innovations**, shown to everyone.
- **Lines 87–93**: **Examinateur**, shown to everyone. The "10 réservations" page itself checks for the admin role, so other users get "Accès refusé".
- **Lines 95–103**: a login link when logged out, a logout link when logged in.

### 8.4 fragment/fragmentFooter.html

- **Lines 1–7**: a centred footer with the course (LO07), the authors and the school (UTT), between two horizontal lines (`<hr/>`).

### 8.5 accueil/viewAccueil.php

- **Lines 5–8**: the home page banner. `mt-4` (margin top), `p-5` (padding), `bg-primary` (orange background, from the header's override), `text-white` and `rounded` are Bootstrap utility classes: each one applies one small piece of style.

### 8.6 utilisateur/ views

**viewLogin.php**

```html
6  <form method="get" action="router.php">
7      <input type="hidden" name="action" value="logined">
8      <div class="mb-3">
9          <label for="login" class="form-label">Login:</label>
10         <input type="text" class="form-control" id="login" name="login" required>
...
14         <input type="password" class="form-control" id="password" name="password" required>
...
16     <button type="submit" class="btn btn-primary">Connexion</button>
```

- **Line 6**: submitting sends the fields to `router.php` as URL parameters (`method="get"`).
- **Line 7**: a hidden field: `action=logined` is sent along without being shown, which tells the router to call `ControllerUtilisateur::logined()`. Every form in the app uses this trick.
- **Lines 9–10**: `for="login"` links the label to the field with `id="login"` (clicking the label focuses the field). `name="login"` is the key the controller reads in `$_GET['login']`. `required` stops the browser from submitting an empty field.
- **Line 14**: `type="password"` hides what's typed.

**viewAll.php** draws a table of users:

- **Lines 6–16**: `<table>` with Bootstrap classes (`table-striped` alternates row colours, `table-bordered` adds borders). `<thead>` is the header row; `<th>` is a header cell.
- **Lines 18–27**: one `<tr>` (row) per user, with one `<td>` (cell) per column. **Line 25** formats the balance with 2 decimals and a € sign.

**viewInsertConducteur.php** and **viewInsertPassager.php** are the same form with a different hidden `action` (line 7): name, first name, login, password, and starting balance. `type="number" step="0.01"` (line 26) accepts amounts with cents, and `value="100"` pre-fills 100.

**viewInserted.php** shows the new id (line 6) and a button back to the list (line 7). The `class="btn btn-primary"` on a link makes it look like a button.

### 8.7 vehicule/ views

- **viewAll.php**: like the user table. **Lines 18–21** run a query per vehicle to look up the owner's name from `proprietaire_id`, with `N/A` if not found.
- **viewInsert.php**: form for brand, model, year and plate. **Lines 26–31**: a `<select>` dropdown with one `<option>` per driver. The option's `value` is the driver's id (what's sent), and its text is their name (what's shown).
- **viewInserted.php**: confirmation with the new id.
- **viewMesVehicules.php**: **line 6** shows the table only if the driver has vehicles (`count($vehicules) > 0`); **line 30** otherwise shows a message.

### 8.8 ville/ views

- **viewAll.php**: table of id and name.
- **viewInsert.php**: one field, the city name.
- **viewInserted.php**: confirmation.

### 8.9 trajet/ views

- **viewInsert.php**: the trip form. **Lines 9–25**: two dropdowns of cities (departure, arrival). **Lines 27–34**: the driver's vehicles. **Lines 36–46**: price, date (`type="date"` shows a calendar picker) and time (`type="time"`).
- **viewMesTrajets.php**: the driver's trips. **Lines 21–24** look up the city names from their ids for every row.
- **viewSelectTrajetPassagers.php**: a dropdown of the driver's trips, labelled `departure -> arrival (date)`. Submitting calls `trajetPassagers` again, now with `trajet_id`.
- **viewPassagers.php**: one row per booking, with the passenger's name and login looked up by id (lines 17–19).
- **viewSelectTrajetCloture.php**: like the passenger selector, but **line 13** only lists trips that are still `actif`, so closed trips can't be picked.
- **viewClotured.php**: **line 7** shows how many passengers there were (`count($reservations)`), **line 8** the total received (passengers × price).
- **viewInserted.php**: confirmation.

### 8.10 reservation/ views

- **viewInsert.php**: a dropdown of every active trip, with route, date, time and price in each option (line 18).
- **viewMesReservations.php**: the passenger's bookings. For each one, **lines 24–28** load the trip, then its driver, vehicle and both cities, to show everything in one row: driver, car, plate, route, date, time, price and status. **Lines 30–34** fall back to `N/A` if any of them is missing.
- **viewInserted.php**: confirmation with a link to "my bookings".

### 8.11 innovations/viewInnovations.php

A static page presenting the project's ideas as Bootstrap **cards** (a box with a coloured `card-header` and a `card-body`):

- **Lines 11–27**: the virtual balance system (real vs virtual balance, the check when booking, the live recalculation).
- **Lines 29–43**: a proposed improvement, the **Repository pattern**: an extra layer between controllers and models, so controllers don't need to know whether data comes from SQL, a file or an API, and can be tested with fake data.
- **Lines 45–56**: access control by role and atomic SQL transactions.

### 8.12 examinateur/ views

**viewSuperglobales.php** displays PHP's **superglobals**, built-in variables available everywhere:

- **Lines 14–19**: every `$_SESSION` entry as a table row. `json_encode` turns an array into text so it can be displayed.
- **Lines 30–43**: `$_COOKIE`, the cookies the browser sent (including the session id cookie), or "Aucun cookie".
- **Lines 52–65**: `$_GET`, the URL parameters.
- **Lines 76–95**: a selection of `$_SERVER`: the host name, the HTTP method (`GET`), the script path, the full requested URL, and the visitor's IP address. `?? 'N/A'` covers values that don't exist.

**viewReservationsAleatoires.php**:

- **Line 7**: how many bookings were created.
- **Lines 10–31**: if any, a table of id, passenger and route from `$added_details`; otherwise an error message.
- **Line 35**: a button that runs the same action again for 10 more.

---

## 9. blablacar2026.sql

The database schema and test data.

- **Lines 6–10**: delete the tables if they exist, so the file can be imported again to reset everything. The order matters: tables that point to others (`reservation` → `trajet` → `ville`, `vehicule` → `utilisateur`) are dropped first, otherwise MySQL refuses because of the foreign keys.

**`utilisateur`** (lines 16–25):

```sql
create table if not exists utilisateur (
 id int not null,
 nom varchar(40) not null,
 prenom varchar(40) not null,
 role enum('administrateur', 'conducteur', 'passager'),
 login varchar(20) not null,
 password varchar(20) not null,
 solde decimal(8,2),
 primary key (id)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
```

- `int`: whole number. `varchar(40)`: text up to 40 characters. `not null`: the field must have a value.
- `enum(...)`: only one of the listed values is accepted.
- `decimal(8,2)`: an exact number with 8 digits, 2 after the decimal point (up to 999999.99). Used for money because it never has rounding errors, unlike `float`.
- `primary key (id)`: `id` uniquely identifies each row.
- `ENGINE=InnoDB`: the MySQL storage engine that supports transactions and foreign keys.
- **Lines 28–52**: the test users: admin `boss` (id 0), drivers 101–104, passengers 201–207, 301–304 and 401–406, each with a starting balance of 1000 or 1500 (0 for the admin).

**`vehicule`** (lines 58–66): `foreign key (proprietaire_id) references utilisateur(id)` makes MySQL refuse a vehicle whose owner doesn't exist. **Lines 68–77**: 10 vehicles, three or four per driver 101–103.

**`ville`** (lines 83–86): `unique` forbids two cities with the same name. **Lines 88–137**: 50 French cities.

**`trajet`** (lines 143–157): four foreign keys (both cities, driver, vehicle). `date` and `time` store the departure. **Lines 160–164**: 5 active trips in July 2026.

**`reservation`** (lines 170–176): links a trip and a passenger. **Line 178**: no bookings at the start, so every passenger's virtual balance equals their real balance.

---

## 10. Docker files

These let anyone run the app with one command, without installing PHP or MySQL.

**Dockerfile**

```dockerfile
FROM php:8.2-apache
RUN docker-php-ext-install pdo_mysql
```

- `FROM`: starts from an official image that already contains PHP 8.2 and the Apache web server.
- `RUN docker-php-ext-install pdo_mysql`: adds the PDO driver for MySQL, which `Model.php` needs.
- The code itself isn't copied into the image: `docker-compose.yml` mounts the project folder into the container, so edits show up immediately.

**docker-compose.yml** starts three containers that can reach each other by name:

- **`web`**: builds the Dockerfile. `ports: "8080:80"` makes Apache's port 80 reachable at `localhost:8080`. `environment` sets the `DB_*` variables that `config.php` reads (lines 19–22), with `DB_HOST: db` = the database container. `volumes: ./:/var/www/html` puts the project in Apache's web folder. `depends_on ... service_healthy` waits until MySQL is ready before starting.
- **`db`**: MySQL 8. `command: --character-set-server=utf8mb4 ...` makes the server use UTF-8 so accents are stored correctly. The `MYSQL_*` variables create the `blablacar` database and its user. The first `volumes` line places `blablacar2026.sql` in `/docker-entrypoint-initdb.d/`, a folder whose `.sql` files MySQL runs automatically the first time it starts. `db_data` keeps the data between restarts. The `healthcheck` pings MySQL every 5 seconds until it answers.
- **`phpmyadmin`**: a web interface for the database at `localhost:8081`, already logged in.

**.gitignore** keeps `config.local.php` (real passwords) and editor files off GitHub. **.dockerignore** keeps them out of Docker builds.

---

## 11. Following one request from start to finish

A passenger books trip 3. Here is everything that runs.

1. The browser loads `router.php?action=reservationCreate`.
2. `router.php` starts the session (line 2), loads the controllers (6–11), reads `action` (14), and calls `ControllerReservation::create()`.
3. `create()` checks the role is `passager`, calls `ModelTrajet::getActifs()`, then `require`s `viewInsert.php`.
4. `getActifs()` calls `Model::getInstance()`. On the first call of the request, that loads `config.php` and opens the PDO connection. Then it runs `SELECT * FROM trajet WHERE statut = 'actif'` and returns `ModelTrajet` objects.
5. `viewInsert.php` includes the header and the menu (which calls `getVirtualSolde` for the ⏳ balance), then prints one `<option>` per trip, then the footer.
6. The passenger picks trip 3 and clicks **Réserver**. The browser loads `router.php?action=reservationCreated&trajet_id=3`.
7. The router calls `ControllerReservation::created()`, which reads `trajet_id`, loads trip 3, and computes the virtual balance.
8. If the balance is enough, `ModelReservation::insert(3, <passenger id>)` checks for an existing booking, computes the new id and runs the `INSERT`.
9. `viewInserted.php` shows the booking id. The ⏳ balance in the menu has dropped by the trip's price; the 💰 balance hasn't changed yet.
10. Later, the driver closes trip 3. `ControllerTrajet::cloture()` runs its transaction: trip → `passif`, the passenger's 💰 goes down, the driver's goes up, and the ⏳ balance is back to matching 💰.
