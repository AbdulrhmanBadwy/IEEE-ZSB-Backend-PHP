
# OOP Part-1

## Table of Contents
- [Class vs Object](#class-vs-object-in-programming)
- [Access Modifiers (Encapsulation)](#access-modifiers-encapsulation-in-php)
- [Typed Properties](#typed-properties-in-php)
- [Constructor Methods](#constructor-methods-in-php)
- [Class vs Object in PHP Example](#what-is-a-class-vs-object-in-php)

---

## Class vs. Object in Programming

### What is a Class?
A **Class** is a **blueprint** or **template** for creating objects.  
It defines the **properties** (attributes) and **behaviors** (methods) that the objects will have, but it does **not** occupy memory itself.

### What is an Object?
An **Object** is a **real instance** created from a class.  
It is the actual thing that exists in memory and can be used in the program.

---

### Real-World Analogy: Car Factory

Think of a **Class** as the **engineering design** or **blueprint** of a car.

- The blueprint specifies:
  - How many wheels it has
  - The color options
  - The engine size
  - The functions (accelerate, brake, turn on lights, etc.)

→ You can have **one blueprint** (Class), but you can manufacture **many cars** from it.

Now, each **actual car** that comes out of the factory is an **Object** (instance of the Car class).

#### Example:

- **Class**: `Car` (the blueprint)
  - Attributes: `color`, `model`, `year`, `speed`
  - Methods: `start()`, `accelerate()`, `brake()`, `stop()`

- **Objects** (instances):
  - `myToyota = Car("Red", "Camry", 2024)` → This is a real red Toyota Camry
  - `yourTesla = Car("White", "Model Y", 2025)` → This is a real white Tesla

Even though both cars were created from the same `Car` class (same blueprint), they are **different objects** with their own values (different colors, models, etc.).

---

### Summary Table

| Concept     | Class                          | Object                          |
|-------------|--------------------------------|---------------------------------|
| Meaning     | Blueprint / Template           | Real instance / Actual thing    |
| Memory      | Does not occupy memory         | Occupies memory                 |
| Exists      | Only once (definition)         | Multiple instances possible     |
| Analogy     | Car design drawing             | Actual manufactured cars        |
| Example     | `class Car:`                   | `car1 = Car("Red")`             |

---

### Key Point to Remember

> **A Class is like a cookie cutter.**  
> **An Object is like the actual cookie** made from that cutter.

You design the cutter once (Class), but you can make as many cookies (Objects) as you want — each can have different toppings (different property values).

## What is a Class vs Object in PHP

### What is a Class?
A **Class** is a **blueprint** or **template** used to define the structure and behavior of objects.  
It describes what data (properties) and actions (methods) an object will have, but it does **not represent a real entity in memory by itself**.

A class acts as a design that can be reused to create multiple objects with similar structure.

---

### What is an Object?
An **Object** is an **instance of a class**.  
It represents a real entity created based on the class blueprint and **occupies memory** when instantiated.

Each object contains its own set of values for the properties defined in the class and can execute the methods of that class.

---

### Real-World Analogy: Car Factory

A **Class** can be compared to a **car blueprint or engineering design**.

The blueprint defines:
- Number of wheels  
- Engine type  
- Available colors  
- Functional capabilities (start, stop, accelerate, brake)

This blueprint exists only as a design and does not represent a physical car.

An **Object**, on the other hand, is an **actual car produced using that blueprint**.

You can manufacture multiple cars from the same blueprint, and each car:
- Exists physically  
- Has its own specific attributes (color, model, year)

---

### Example

#### Class Definition
```php
class Car {
    public $color;
    public $model;
    public $year;

    public function start() {
        return "Car started";
    }

    public function stop() {
        return "Car stopped";
    }
}
```
#### Object Creation 
```php
$car1 = new Car();
$car1->color = "Red";
$car1->model = "Camry";
$car1->year = 2024;

$car2 = new Car();
$car2->color = "White";
$car2->model = "Model Y";
$car2->year = 2025;
```

## Access Modifiers (Encapsulation) in PHP

### Overview
Access modifiers control how class properties and methods can be accessed. They support encapsulation, which means protecting data and controlling how it is used.

### Types of Access Modifiers

#### public
Accessible from anywhere: inside the class, outside the class, and in child classes.
Example:
class User {
    public $name = "Badwy";
}
$user = new User();
echo $user->name;

#### protected
Accessible only inside the class and its child classes. Not accessible from outside.
Example:
class User {
    protected $email = "test@example.com";
}
class Admin extends User {
    public function getEmail() {
        return $this->email;
    }
}

#### private
Accessible only inside the same class. Not accessible from outside or child classes.
Example:
class User {
    private $password = "123456";
    public function getPassword() {
        return $this->password;
    }
}

### Comparison
public     → class: yes | child: yes | outside: yes  
protected  → class: yes | child: yes | outside: no  
private    → class: yes | child: no  | outside: no  

### Why use private?
To protect sensitive data and enforce rules.
Example:
```php
class BankAccount {
    private $balance = 0;

    public function deposit($amount) {
        if ($amount > 0) {
            $this->balance += $amount;
        }
    }

    public function getBalance() {
        return $this->balance;
    }
}

If balance was public:
$account->balance = -1000; // invalid
```
### Summary
public → full access  
protected → class and inheritance only  
private → class only  

Use private to ensure data safety and controlled access.

---

## Typed Properties in PHP

### Overview
Typed Properties allow you to define the **data type of a class property** explicitly (e.g., int, string, bool).  
They were introduced in PHP 7.4 to improve code reliability and reduce bugs.

---

### Without Typed Properties

Before PHP 7.4, properties could hold **any type of value**, which could lead to unexpected errors.

Example:
```php
class User {
    public $age;
}


$user = new User();
$user->age = "twenty"; // Allowed (but logically wrong)
```
---

### With Typed Properties

You can enforce the type of a property directly.

Example:
```php
class User {
    public int $age;
}

$user = new User();
$user->age = 20;      // Correct
$user->age = "twenty"; // Error (Type mismatch)
```
---

### Common Types

- int
- float
- string
- bool
- array
- object
- class types (e.g., User, Car)

Example:
```php
class Product {
    public string $name;
    public float $price;
}
```
---

### Nullable Types

You can allow null values using `?`

Example:
```php
class User {
    public ?string $email = null;
}
```
---

### How Typed Properties Prevent Bugs

#### 1. Early Error Detection
Wrong data types cause immediate errors instead of hidden bugs.

#### 2. Better Data Integrity
Ensures properties always hold valid values.

#### 3. Improved Readability
Developers know exactly what type each property should be.

#### 4. Safer Refactoring
Reduces unexpected behavior when modifying code.

---

### Comparison

Without Type:
- Accepts any value
- Errors appear later (runtime logic bugs)

With Type:
- Accepts only defined type
- Errors appear immediately

---

### Summary
Typed Properties enforce strict data types for class properties, helping prevent invalid assignments, improving code clarity, and reducing runtime bugs.
---

## Constructor Methods in PHP

### Overview
The `__construct()` method is a special method in PHP that is **automatically called when a new object is created** from a class.  
It is mainly used to **initialize object properties** and set up the object’s initial state.

---

### Basic Example
```php
class User {
    public string $name;

    public function __construct($name) {
        $this->name = $name;
    }
}

$user = new User("Badwy");
```
---

### What is it used for?

- Initializing properties when the object is created  
- Assigning default or passed values  
- Preparing the object to be ready for use immediately  

---

### Why pass arguments into the constructor?

#### 1. Initialize Data at Creation
Instead of creating an empty object and setting values later, you can initialize everything in one step.

Without constructor:
```php
class User {
    public string $name;
}

$user = new User();
$user->name = "Badwy";

With constructor:
$user = new User("Badwy");
```
---

#### 2. Ensure Required Data Exists
You can force certain values to be provided when creating the object.

Example:
```php
class Product {
    public string $name;

    public function __construct($name) {
        $this->name = $name;
    }
}

$product = new Product("Laptop"); // Required value
```
---

#### 3. Reduce Errors
Prevents forgetting to set important properties later in the code.

---

#### 4. Cleaner and More Readable Code
Makes object creation shorter and more organized.

---

### Advanced Example
```php
class BankAccount {
    private float $balance;

    public function __construct($balance) {
        if ($balance >= 0) {
            $this->balance = $balance;
        } else {
            $this->balance = 0;
        }
    }
}

$account = new BankAccount(1000);
```
---

### Summary
The `__construct()` method is used to initialize an object when it is created.  
Passing arguments to the constructor ensures that objects start with valid data, reduces errors, and makes code cleaner and more reliable.