# Task 19 - mvc-part-3

## Table of Contents

1. [Who Talks to the Database?](#1-who-talks-to-the-database)
2. [Sensitive Configuration Files](#2-sensitive-configuration-files)
3. [What is PDO?](#3-what-is-pdo)
4. [Prepared Statements & SQL Injection](#4-prepared-statements--sql-injection)
5. [Fetching One Row vs. Many Rows](#5-fetching-one-row-vs-many-rows)

---

## 1. Who Talks to the Database?

**Question:** In the MVC pattern, what is the only part of the application that should be allowed to talk directly to the database? Why?

The **Model** is the only layer in MVC that is permitted to communicate directly with the database. No other part of the application — not the Controller, not the View — should ever write a raw SQL query or open a database connection on its own.

### Why Only the Model?

| Reason | Explanation |
|---|---|
| **Single Responsibility** | Each layer has one job. The Model's job is to manage data — reading it, writing it, and validating it. Giving the Controller or View database access blurs these boundaries |
| **Reusability** | If the Controller needed the same user record in five different places, you would write the same SQL five times. With a Model method like `UserModel::find($id)`, you write the logic once and call it everywhere |
| **Easier Maintenance** | When your database schema changes (e.g. a column is renamed), you only update code in one place — the Model — instead of hunting through Controllers and View files |
| **Security** | Centralizing all database access in the Model makes it far easier to enforce secure practices (like prepared statements) consistently. Raw queries scattered across Controllers and Views are easy to miss during a security review |
| **Testability** | You can write automated tests for a Model method in isolation. You cannot easily test database logic that is buried inside a Controller action or mixed into a View template |

### How the Flow Works

```
View       →  displays output only — never touches the database
Controller →  calls Model methods — never writes SQL directly
Model      →  owns all SQL and database logic — the single gatekeeper
Database   →  only ever spoken to by the Model
```

> **The analogy:** Think of the Model as the only employee who has a key to the supply room (the database). Everyone else must ask that employee to fetch what they need. This way, there is always one controlled, accountable point of access — no one else can wander in and cause chaos.

---

## 2. Sensitive Configuration Files

**Question:** Why should sensitive information (like database passwords) be stored in a separate configuration file instead of being hardcoded in your main application files?

Hardcoding credentials directly into your application files is one of the most common and dangerous mistakes a developer can make. A dedicated configuration file solves several critical problems at once.

### The Problem with Hardcoding

```php
// ❌ BAD — credentials buried directly in application logic
class Database {
    private $host     = 'localhost';
    private $dbname   = 'my_app_db';
    private $username = 'root';
    private $password = 'SuperSecret123!';
}
```

If this file is ever pushed to a public GitHub repository, your database password is now visible to the entire internet — permanently, because Git history does not forget.

### The Solution — A Dedicated Config File

```php
// config/database.php — stored separately, excluded from version control
return [
    'host'     => 'localhost',
    'dbname'   => 'my_app_db',
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
];
```

```
// .gitignore — config files are never committed to the repository
config/database.php
.env
```

### Why This Matters

| Risk | Without Config File | With Config File |
|---|---|---|
| **Version Control Exposure** | Credentials committed to Git history | Config file excluded via `.gitignore` |
| **Team Collaboration** | Every developer sees production passwords | Each developer uses their own local config |
| **Environment Switching** | Code must be manually edited for dev/staging/production | Config file swapped per environment — code stays the same |
| **Breach Damage** | One leaked file exposes everything | Credentials are isolated and can be rotated without touching application code |
| **Audit Trail** | Secrets mixed into thousands of lines of code | All sensitive values in one known, controlled location |

### The `.env` Pattern

Modern frameworks use a `.env` file at the project root — a simple key-value store that is always excluded from version control:

```
DB_HOST=localhost
DB_NAME=my_app_db
DB_USER=root
DB_PASS=SuperSecret123!
```

The application reads these values at runtime using `getenv('DB_PASS')` or a library like `vlucas/phpdotenv`. The actual `.env` file never leaves the server.

> **The rule:** If the information would cause damage if it appeared on a public GitHub page, it belongs in a config file — not in your source code.

---

## 3. What is PDO?

**Question:** What is PDO in PHP, and why is it preferred over older methods like `mysqli`?

**PDO** stands for **PHP Data Objects**. It is a database abstraction layer built into PHP that provides a consistent, object-oriented interface for interacting with databases. Instead of writing database-specific code, you write standard PDO code and it handles the differences underneath.

### Connecting with PDO

```php
// Establishing a PDO connection
$dsn = "mysql:host=localhost;dbname=my_app_db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, 'username', 'password', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
```

### PDO vs. `mysqli` — The Key Differences

| Feature | PDO | `mysqli` |
|---|---|---|
| **Database Support** | 12+ databases (MySQL, PostgreSQL, SQLite, SQL Server…) | MySQL only |
| **API Style** | Object-oriented only | Object-oriented or procedural |
| **Prepared Statements** | Fully supported, clean syntax | Supported, but more verbose |
| **Named Parameters** | Yes — `:username`, `:email` | No — positional `?` only |
| **Error Handling** | Throws `PDOException` objects | Returns true/false, requires manual checking |
| **Portability** | Swap databases by changing the DSN string | Full rewrite required to switch databases |

### Why PDO Wins

The biggest practical advantage is **portability**. If a project starts on MySQL and later needs to migrate to PostgreSQL, PDO requires changing only the connection string. With `mysqli`, the entire database layer must be rewritten from scratch because `mysqli` is MySQL-specific by design.

Named parameters also make PDO queries far more readable:

```php
// PDO — named parameters, easy to read and maintain
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND active = :active");
$stmt->execute([':email' => $email, ':active' => 1]);

// mysqli — positional only, order-dependent and error-prone with many parameters
$stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ? AND active = ?");
$stmt->bind_param("si", $email, $active);
$stmt->execute();
```

> **The takeaway:** PDO is the modern standard for database access in PHP. It is more flexible, more readable, and database-agnostic — making your code future-proof in a way that `mysqli` simply cannot be.

---

## 4. Prepared Statements & SQL Injection

**Question:** How do "Prepared Statements" protect your website from SQL Injection attacks?

SQL Injection is an attack where a malicious user inserts raw SQL code into an input field, tricking the database into running commands the developer never intended. Prepared Statements eliminate this vulnerability entirely by separating the SQL structure from the user-supplied data.

### How a SQL Injection Attack Works

Imagine a login form. A developer writes this naive query:

```php
// ❌ VULNERABLE — user input directly concatenated into SQL
$query = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "'";
```

A normal user types `ahmed` and the query becomes:

```sql
SELECT * FROM users WHERE username = 'ahmed'
```

But an attacker types `' OR '1'='1` as their username. The query becomes:

```sql
SELECT * FROM users WHERE username = '' OR '1'='1'
```

`'1'='1'` is always true. The database returns **every user in the table** — the attacker is now logged in without a password.

### How Prepared Statements Block This

A Prepared Statement works in two distinct phases:

**Phase 1 — Prepare:** Send the SQL structure to the database with placeholders, not real values. The database parses, compiles, and locks the query structure at this point.

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
```

**Phase 2 — Execute:** Send the actual user data separately. The database treats this data as a **literal string value only** — it can never be interpreted as SQL syntax.

```php
$stmt->execute([':username' => $_POST['username']]);
```

If the attacker now types `' OR '1'='1`, the database receives it as a raw string to search for literally — not as SQL. The query effectively becomes:

```sql
SELECT * FROM users WHERE username = "' OR '1'='1"
```

No user has that username, so nothing is returned. The attack fails completely.

### The Complete Safe Login Example

```php
// ✅ SAFE — using PDO prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
$stmt->execute([
    ':username' => $_POST['username'],
    ':password' => hash('sha256', $_POST['password'])
]);
$user = $stmt->fetch();
```

### Why This Works

| Stage | What Happens |
|---|---|
| `prepare()` | SQL structure is sent to and compiled by the database — structure is now fixed |
| `execute()` | User data is sent separately as parameters — treated as data, never as code |
| **Result** | No user input can ever alter the query's structure or logic |

> **The key insight:** SQL Injection works by blurring the line between *code* and *data*. Prepared Statements enforce a hard separation between the two — the query structure is defined by the developer, and user input can only ever be data. There is no way to inject code into a slot that the database already knows is a data placeholder.

---

## 5. Fetching One Row vs. Many Rows

**Question:** When you query a database, you can fetch a single row or multiple rows. Give a real-world example of a situation where you need just one row, and a situation where you need an array of multiple rows.

### Fetching a Single Row — `fetch()`

Use `fetch()` when the query is expected to return exactly one result — a specific, uniquely identified record.

**Real-world example: Displaying a User's Profile Page**

When a user navigates to `/profile?id=7`, the application needs the record for user #7 — and only that record. Fetching more would be wasteful and incorrect.

```php
// Controller asks the Model for one specific user
$stmt = $pdo->prepare("SELECT id, name, email, avatar FROM users WHERE id = :id");
$stmt->execute([':id' => $_GET['id']]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);
// Returns: ['id' => 7, 'name' => 'Ahmed', 'email' => 'ahmed@example.com', 'avatar' => 'ahmed.jpg']
```

```php
<!-- View displays the single user object -->
<h1><?php echo $user['name']; ?></h1>
<p><?php echo $user['email']; ?></p>
```

Other situations that call for a single row:
- Fetching a specific blog post by its slug (`/posts/my-first-article`)
- Retrieving a product by its ID for a product detail page
- Looking up an order by its unique order number

---

### Fetching Multiple Rows — `fetchAll()`

Use `fetchAll()` when the query is expected to return a collection of results — a list where the exact count is unknown at the time of writing the code.

**Real-world example: Displaying an Admin's List of All Users**

An admin dashboard page needs to display every registered user in the system. The count changes daily as new users sign up. The application must fetch all of them and loop through the results.

```php
// Controller asks the Model for all active users
$stmt = $pdo->prepare("SELECT id, name, email, created_at FROM users WHERE active = 1 ORDER BY created_at DESC");
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Returns: an array of arrays, one per user row
```

```php
<!-- View loops through the array and renders a row for each user -->
<table>
  <tr><th>Name</th><th>Email</th><th>Joined</th></tr>
  <?php foreach ($users as $user): ?>
    <tr>
      <td><?php echo $user['name']; ?></td>
      <td><?php echo $user['email']; ?></td>
      <td><?php echo $user['created_at']; ?></td>
    </tr>
  <?php endforeach; ?>
</table>
```

Other situations that call for multiple rows:
- Listing all blog posts on a homepage feed
- Showing all items currently in a shopping cart
- Displaying search results for a product query
- Rendering a leaderboard with all player scores

### Quick Comparison

| | `fetch()` — Single Row | `fetchAll()` — Multiple Rows |
|---|---|---|
| **Returns** | One associative array | An array of associative arrays |
| **When to use** | One specific, uniquely identified record | A list of records matching a condition |
| **Real-world example** | User profile page for user #7 | Admin panel listing all registered users |
| **View pattern** | Access keys directly: `$user['name']` | Loop with `foreach`: `foreach ($users as $user)` |
| **If no result** | Returns `false` | Returns an empty array `[]` |

> **The rule of thumb:** If you are fetching by a unique identifier (primary key, unique slug, unique email), use `fetch()`. If you are fetching by a condition that could match any number of rows, use `fetchAll()` and loop through the results in the View.

---

*Research answers compiled for Web Development fundamentals — MVC Database Layer, Configuration Security, PDO, Prepared Statements, and Single vs. Multiple Row Fetching.*