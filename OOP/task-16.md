# OOP part 3 

## Table of Contents

1. [Traits](#1-traits)
2. [Namespaces](#2-namespaces)
3. [Autoloading](#3-autoloading)
4. [Magic Methods — `__get` and `__set`](#4-magic-methods----get-and-set)
5. [Static Methods and Properties](#5-static-methods-and-properties)

---

## 1. Traits

### The Problem

PHP follows a **single inheritance** model, which means a class can only extend one parent class. This creates a limitation when you want to reuse the same set of methods across multiple unrelated classes. For example, if two completely different classes — say `User` and `Post` — both need a `log()` method, you cannot simply inherit it from two parents at the same time.

### How Traits Solve It

A **Trait** is essentially a reusable block of code — a group of methods — that you can "inject" into any class, no matter where that class sits in the inheritance chain. Think of it as a copy-paste mechanism managed by PHP itself: when you write `use TraitName;` inside a class, PHP takes all the methods defined in that trait and makes them available in the class, as if you had written them there directly.

```php
trait Loggable {
    public function log($message) {
        echo "[LOG]: " . $message;
    }
}

class User {
    use Loggable;
}

class Post {
    use Loggable;
}

$user = new User();
$user->log("User created."); // Output: [LOG]: User created.
```

A class can also use **multiple traits at once**, which effectively simulates multiple inheritance:

```php
class Article {
    use Loggable, Timestampable, Cacheable;
}
```

### When Should You Use Traits?

You should use a Trait when:

- You have a set of methods that need to be shared across **multiple unrelated classes**.
- The shared behavior does **not** represent a parent-child relationship (otherwise, regular inheritance would be more appropriate).
- You want to keep your code **DRY** (Don't Repeat Yourself) without forcing an artificial class hierarchy.

> **In short:** Use Traits for shared behaviors; use Inheritance for shared identity.

---

## 2. Namespaces

### What Is a Namespace?

A **Namespace** in PHP is a way to group related classes, functions, and constants under a unique organizational label — similar to how folders on a computer help you organize files with the same name in different locations. Without namespaces, every class name in your entire project must be unique, which becomes nearly impossible in large applications or when using third-party libraries.

### How It Prevents Naming Collisions

Imagine you are building an application that uses two different libraries — both of which happen to define a class called `Logger`. Without namespaces, PHP would throw a fatal error because it cannot distinguish between the two. With namespaces, each `Logger` class lives in its own "folder," so PHP knows exactly which one you mean.

```php
// File: App/Logger.php
namespace App;

class Logger {
    public function log($msg) {
        echo "App Logger: " . $msg;
    }
}
```

```php
// File: ThirdParty/Logger.php
namespace ThirdParty;

class Logger {
    public function log($msg) {
        echo "Third-Party Logger: " . $msg;
    }
}
```

```php
// Using both in the same file — no conflict
use App\Logger as AppLogger;
use ThirdParty\Logger as ExternalLogger;

$a = new AppLogger();
$b = new ExternalLogger();

$a->log("Hello!"); // App Logger: Hello!
$b->log("Hello!"); // Third-Party Logger: Hello!
```

The `use ... as ...` keyword creates an **alias**, allowing both classes to coexist in the same file without any conflict.

### Key Takeaway

Namespaces give each class a **fully qualified identity** (e.g., `App\Models\User` vs `Admin\Models\User`), making large projects and third-party integrations manageable and collision-free.

---

## 3. Autoloading

### The Old Way (Before Autoloading)

Before autoloading existed, a developer had to manually `require` or `include` every single class file at the top of each PHP script. In a project with dozens or hundreds of classes, this quickly became unmanageable:

```php
// Old approach — manual and error-prone
require 'classes/User.php';
require 'classes/Post.php';
require 'classes/Comment.php';
require 'classes/Database.php';
// ... and so on for every file
```

If you forgot one `require`, your script would crash. If you added a new class, you had to remember to add a new `require` everywhere it was used.

### What Is Autoloading?

**Autoloading** is a mechanism that tells PHP: *"Whenever you encounter a class that hasn't been loaded yet, automatically find and include its file — without me asking."*

Instead of manually listing every file, you register an autoloader function once, and PHP calls it automatically whenever a new class is needed.

```php
// Simple custom autoloader
spl_autoload_register(function ($className) {
    require 'classes/' . $className . '.php';
});

// Now you can use any class directly — no require needed!
$user = new User();
$post = new Post();
```

### PSR-4 and Composer Autoloading

In modern PHP development, the standard approach is to use **Composer**, which follows the **PSR-4** autoloading standard. You define a namespace-to-folder mapping in `composer.json`, run `composer dump-autoload`, and a single line handles everything:

```php
require 'vendor/autoload.php'; // That's all you need
```

### How It Saves Time

| Before Autoloading | With Autoloading |
|---|---|
| Manual `require` for every class | One-time autoloader setup |
| Easy to forget a file | PHP loads files on demand automatically |
| Messy top-of-file includes | Clean, minimal code |
| Error-prone in large projects | Scales effortlessly |

> **In short:** Autoloading eliminates repetitive file management and lets you focus on writing logic instead of tracking file paths.

---

## 4. Magic Methods — `__get` and `__set`

### What Are Magic Methods?

**Magic methods** in PHP are special predefined methods that start with a double underscore (`__`). They are not called by you directly — PHP calls them **automatically** when certain events or conditions occur. The `__get` and `__set` methods are specifically designed to handle access to **inaccessible or non-existent properties**.

### `__get($name)` — Triggered on Read

The `__get` magic method is automatically called when your code tries to **read** a property that either does not exist or is not accessible (e.g., it is `private` or `protected`).

```php
class Profile {
    private array $data = ['username' => 'Ahmed', 'age' => 22];

    public function __get($name) {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return "Property '$name' not found.";
    }
}

$p = new Profile();
echo $p->username; // Triggers __get → Output: Ahmed
echo $p->email;    // Triggers __get → Output: Property 'email' not found.
```

### `__set($name, $value)` — Triggered on Write

The `__set` magic method is automatically called when your code tries to **assign a value** to a property that is inaccessible or does not exist.

```php
class Profile {
    private array $data = [];

    public function __set($name, $value) {
        $this->data[$name] = $value;
        echo "Set '$name' to '$value'\n";
    }
}

$p = new Profile();
$p->username = "Sara"; // Triggers __set → Output: Set 'username' to 'Sara'
```

### When Are They Useful?

These methods are useful when you want to:

- **Control access** to properties — for example, validating or sanitizing a value before storing it.
- **Build flexible objects** that store dynamic data without pre-declaring every property.
- **Implement proxy objects** or data-mapping layers where properties map to database columns or API fields.

> **Important:** `__get` and `__set` only trigger when direct access to the property **fails**. If the property is `public` and exists, PHP uses it directly and never calls these methods.

---

## 5. Static Methods and Properties

### What Does `static` Mean?

When a method or property is declared as `static`, it **belongs to the class itself** — not to any specific instance (object) of that class. This means the method or property exists at the class level and is shared across all objects of that class.

```php
class Counter {
    public static int $count = 0;

    public static function increment() {
        self::$count++;
    }
}
```

### Do You Need `new` to Access a Static Method?

**No.** You access static methods and properties using the **Scope Resolution Operator (`::`)**, directly on the class name — no object creation required.

```php
Counter::increment();
Counter::increment();
Counter::increment();

echo Counter::$count; // Output: 3
```

Contrast this with a regular (instance) method, which requires an object:

```php
// Regular method — requires new
$obj = new Counter();
$obj->regularMethod();

// Static method — no new needed
Counter::staticMethod();
```

### When Should You Use Static?

Static methods and properties are appropriate when:

- The value or behavior is **shared across all instances** (e.g., a counter, a configuration value, or a registry).
- The method does **not depend on any object state** — it works the same no matter which object calls it.
- You want to implement **utility/helper methods** (e.g., `MathHelper::square(4)`).
- You are implementing the **Singleton** or **Factory** design patterns.

### Important Consideration

Because static properties are shared, changing them in one place affects all usages everywhere. This can lead to unexpected behavior if not handled carefully.

```php
class Config {
    public static string $environment = 'production';
}

// Anywhere in the application:
Config::$environment = 'testing'; // Affects the ENTIRE application
```

> **Rule of thumb:** Use `static` for data and behavior that is truly global to the class, not specific to one object.

---

## Summary Table

| Concept | Purpose | Key Syntax |
|---|---|---|
| **Traits** | Reuse methods across unrelated classes | `trait Name {}` / `use Name;` |
| **Namespaces** | Prevent naming collisions in large projects | `namespace App;` / `use App\Class;` |
| **Autoloading** | Auto-include class files on demand | `spl_autoload_register()` / Composer |
| **`__get` / `__set`** | Intercept reads/writes to inaccessible properties | Called automatically by PHP |
| **Static** | Class-level methods/properties, no object needed | `static` keyword / `ClassName::method()` |

---

*Document prepared as part of PHP OOP coursework research.*