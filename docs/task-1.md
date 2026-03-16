# PHP Course Summary

## 1. Introduction & Environment Setup

The course begins by explaining what PHP is:  
A **server-side language** used to generate HTML dynamically.

### Built-in Server

Instead of using complex environments, the course uses the PHP built-in server.

```bash
php -S localhost:8888
```

### Basic Syntax

- Variables start with `$`
- Strings are concatenated using `.`
- Every statement ends with `;`

Example:

```php
$name = "John";
echo "Hello " . $name;
```

---

## 2. Logic and Data Collections

This section introduces ways to manage collections of data.

### Arrays

#### Indexed Arrays

Simple lists of items.

```php
$books = ["Book 1", "Book 2", "Book 3"];
```

#### Associative Arrays

Key-value pairs.

```php
$book = [
    'title' => 'The Great Gatsby',
    'author' => 'F. Scott Fitzgerald'
];
```

### Loops

The `foreach` loop is used to iterate through arrays and render dynamic content.

```php
foreach ($books as $book) {
    echo $book;
}
```

### Conditionals

Used to control logic like highlighting navigation links.

```php
if ($isActive) {
    echo "active";
}
```

---

## 3. Filtering & Functions

This section focuses on writing **clean and reusable code**.

### Functions

Functions help avoid repetition by wrapping logic in reusable blocks.

```php
function greet($name) {
    return "Hello " . $name;
}
```

### Lambda Functions

Anonymous functions used when defining small logic blocks.

```php
$numbers = [1,2,3];

$filtered = array_filter($numbers, function($num){
    return $num > 1;
});
```

### array_filter

Used to filter data based on conditions.

Example: Filtering books by author or release year.

```php
$books = array_filter($books, function ($book) {
    return $book['year'] > 2000;
});
```

---

## 4. Technical Architecture (Separation of Concerns)

This is where the course transitions from **simple scripting to application architecture**.

### Partial Files

Using `require()` to split layout into reusable components.

Example structure:

```
views/
  header.php
  nav.php
  footer.php
```

Example usage:

```php
require "partials/header.php";
```

### Controller / View Pattern

Separating logic from presentation.

Example:

```
index.php        -> Controller (logic)
index.view.php   -> View (HTML layout)
```

Controller Example:

```php
$books = fetchBooks();
require "index.view.php";
```

View Example:

```php
foreach ($books as $book) {
    echo $book['title'];
}
```

### Routing

Creating a simple router using:

```php
$_SERVER['REQUEST_URI']
```

This allows clean URLs like:

```
/about
/contact
/books
```

---

## 5. Databases & SQL

This section introduces **persistent data storage**.

### MySQL & TablePlus

Learning basic SQL queries.

```sql
SELECT * FROM books;
```

```sql
SELECT * FROM books WHERE id = 1;
```

### PDO (PHP Data Objects)

Used to connect PHP with MySQL.

Example connection:

```php
$pdo = new PDO("mysql:host=localhost;dbname=test", "root", "");
```

### fetchAll()

Used to retrieve multiple records.

```php
$statement = $pdo->query("SELECT * FROM books");
$books = $statement->fetchAll();
```

### Security: SQL Injection

Never insert user input directly into SQL queries.

Unsafe example:

```php
$query = "SELECT * FROM users WHERE id = $id";
```

Safe example using prepared statements:

```php
$statement = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$statement->execute([$id]);
```

Or with named parameters:

```php
$statement = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$statement->execute([
    'id' => $id
]);
```

---

## 6. Introduction to Classes (OOP)

Organizing database logic using **Object-Oriented Programming**.

### Database Class

Creating a reusable class to manage database queries.

```php
class Database {

    public $connection;

    public function __construct() {
        $this->connection = new PDO("mysql:host=localhost;dbname=test", "root", "");
    }

    public function query($query) {
        return $this->connection->query($query);
    }
}
```

### Encapsulation

The database class hides connection details so the rest of the application interacts only with the class methods.

---

## Key Helper Functions

Throughout the course, several helper functions are created.

### dd($value)

Dump and Die — used for debugging.

```php
function dd($value) {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}
```

### urlIs($value)

Checks if the current URL matches a specific value.

Useful for highlighting navigation links.

```php
function urlIs($value) {
    return $_SERVER['REQUEST_URI'] === $value;
}
```

---

## Conclusion

This course gradually moves through:

- Basic PHP syntax
- Data structures
- Functions
- Application architecture
- Database integration
- Object-Oriented Programming

The result is a **structured PHP web application** with clean architecture and secure database interaction.