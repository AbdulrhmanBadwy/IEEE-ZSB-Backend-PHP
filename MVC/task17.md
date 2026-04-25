# Web Development Research Questions

> In-depth answers covering core web development concepts: MVC, Routing, Front Controller, Clean URLs, and Separation of Concerns.

---

## Table of Contents

1. [The MVC Pattern](#1-the-mvc-pattern)
2. [Routing](#2-routing)
3. [The Front Controller](#3-the-front-controller)
4. [Clean URLs](#4-clean-urls)
5. [Separation of Concerns](#5-separation-of-concerns)

---

## 1. The MVC Pattern

**MVC** stands for **Model – View – Controller**. It is an architectural pattern that splits an application into three distinct layers, each with one clear job.

| Layer | Responsibility |
|---|---|
| **Model** | Manages data and business logic |
| **View** | Handles what the user sees |
| **Controller** | Acts as the middleman / coordinator |

### Breaking It Down

- **Model** — The "brain" of the data. It talks to the database, applies business rules, and knows nothing about how things look on screen. A `User` model knows how to fetch, save, or validate a user — but it does not care whether the result appears as HTML, JSON, or XML.

- **View** — The "face" of the application. It is purely responsible for presenting data to the user: the HTML templates, the layouts, the buttons. A View receives ready data from the Controller and renders it. It has no idea where the data came from.

- **Controller** — The "traffic coordinator". When a user visits `/users/42`, the Controller receives the request, asks the Model for the right data (`User::find(42)`), then hands that data to the correct View to render a response. It orchestrates without doing the heavy lifting itself.

### Why It Matters

Separating these three roles means you can change your database logic without touching your HTML, and redesign your UI without touching your business rules.

---

## 2. Routing

A **Router** is the component responsible for mapping an incoming URL to the specific piece of code that should handle it.

### The Traffic Cop Analogy

Think of your website as a busy city intersection, and every HTTP request is a car trying to get somewhere.

- The **Router** is the traffic cop standing in the middle.
- When a car (request) arrives, the cop reads the destination on the windshield (the URL, e.g. `/about` or `/products/shoes`).
- Based on that destination, the cop waves the car in the right direction — toward the correct **Controller** and **action**.

```
GET    /contact      →  ContactController@show
GET    /users/42     →  UserController@show
POST   /users        →  UserController@store
DELETE /users/42     →  UserController@destroy
```

Without a Router, every URL would need its own physical file on the server, and there would be no central place to manage or protect traffic. The Router gives you full control over your URL structure, independent of your file structure.

---

## 3. The Front Controller

### Old Approach — Dozens of Separate Files

In early PHP development, every page was a separate file:

```
/about.php
/contact.php
/products.php
/users/profile.php
```

Every file repeated the same setup code — database connections, session handling, authentication checks. Changing any shared behaviour meant editing every single file. Fragile and hard to maintain.

### Modern Approach — The Front Controller (`index.php`)

A **Front Controller** is a single entry point for every request. Every URL — no matter what it is — is routed through one file:

```
example.com/about      →  index.php handles it
example.com/contact    →  index.php handles it
example.com/users/42   →  index.php handles it
```

The web server (via `.htaccess` or Nginx config) funnels all requests to `index.php`. From there, the app boots once, sets up the environment, and hands off to the Router which dispatches to the right Controller.

### Key Benefits

| Old Way (Many Files) | Front Controller (`index.php`) |
|---|---|
| Duplicate setup code everywhere | Bootstrap happens once, in one place |
| Hard to enforce global rules | Apply middleware globally with ease |
| URL = file path on disk | URL structure is freely defined |
| Changing shared logic = editing many files | Change one place, affects everything |

---

## 4. Clean URLs

### Messy URL (Query String Style)
```
example.com/index.php?page=users&action=profile&id=42
```

### Clean URL (RESTful Style)
```
example.com/users/42/profile
```

### Why Clean URLs Are Better

**1. Human Readability**
A clean URL communicates meaning instantly. A visitor can read `example.com/blog/2025/june` and know exactly where they are. The messy version tells them nothing useful.

**2. SEO (Search Engine Optimization)**
Search engines prefer clean URLs. Keywords in the URL path carry ranking weight. `example.com/shoes/red-sneakers` ranks better than `example.com/index.php?cat=3&item=91`.

**3. Security**
Exposing `index.php` in every URL reveals your technology stack to potential attackers. Clean URLs hide implementation details.

**4. Shareability & Trust**
People are far more likely to click and share `example.com/recipes/chocolate-cake` than a long cryptic query string. Clean URLs look professional and trustworthy.

**5. Flexibility**
If you ever switch from PHP to Python or Node.js, your clean URLs stay exactly the same. Users and search engines never notice. With query-string URLs tied to `index.php`, any migration becomes disruptive.

---

## 5. Separation of Concerns

### What Does It Mean?

It is the principle that every part of your code should have **one job** and not bleed into another layer's responsibilities. The Model handles data, the View handles presentation — and they stay out of each other's business.

### The Problem: SQL Inside HTML

Here is what **not** to do:

```php
<!-- user_profile.php — BAD EXAMPLE -->
<html>
<body>
  <h1>User Profile</h1>
  <?php
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id = " . $_GET['id']);
    $user   = mysqli_fetch_assoc($result);
  ?>
  <p>Name: <?= $user['name'] ?></p>
  <p>Email: <?= $user['email'] ?></p>
</body>
</html>
```

### Why This Is a Terrible Idea

**1. Impossible to Maintain**
Your designer editing the layout is now forced to wade through SQL. Your backend developer fixing the query is buried in HTML. Both are working in a minefield.

**2. Security Nightmare**
The example above is wide open to **SQL Injection** — one of the most dangerous web attacks. Raw user input (`$_GET['id']`) fed directly into a query, hidden inside a template, is easy to overlook and hard to audit.

**3. Zero Reusability**
If two pages need the same user data, you copy-paste the SQL query. Now you have two places to update, two places to break, and two security holes to patch.

**4. Untestable Code**
You cannot unit-test a database query glued inside an HTML file. Proper separation means you can test your Model logic completely independently of any output.

**5. Violates Single Responsibility**
An HTML template has one job: display data. The moment it fetches data too, it has two jobs — and complexity multiplies. A single bug could be in the HTML structure, the SQL logic, or the interaction between them.

### The Right Way

```
Controller  →  asks Model for data
Model       →  runs the SQL query, returns a clean result
Controller  →  passes result to View
View        →  displays the data, knows nothing about SQL
```

Each layer is clean, testable, replaceable, and understandable on its own.

---

*Research answers compiled for Web Development fundamentals — MVC, Routing, Front Controller, Clean URLs, and Separation of Concerns.*