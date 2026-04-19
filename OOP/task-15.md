# OOP-part-2

## Table of Contents
1. [Inheritance](#1-inheritance)
2. [The `final` Keyword](#2-the-final-keyword)
3. [Overriding Methods](#3-overriding-methods)
4. [Abstract Class vs. Interface](#4-abstract-class-vs-interface)
5. [Polymorphism](#5-polymorphism)

---

## 1. Inheritance

### What Is It?
Inheritance is a mechanism that allows one class (called the **child** or **subclass**) to acquire the properties and behaviors of another class (called the **parent** or **superclass**). Think of it as a blueprint being passed down — the child gets everything the parent has, and can also add its own unique features on top.

### Main Benefit
The primary benefit of inheritance is **code reusability**. Instead of writing the same code in multiple classes, you define common logic once in a parent class and let all child classes inherit it automatically. This reduces redundancy, makes the codebase easier to maintain, and promotes a logical, hierarchical structure.

### Example

```php
<?php

// Parent Class
class Animal {
    public string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function eat(): void {
        echo $this->name . " is eating.\n";
    }

    public function sleep(): void {
        echo $this->name . " is sleeping.\n";
    }
}

// Child Class
class Dog extends Animal {
    public function bark(): void {
        echo $this->name . " says: Woof!\n";
    }
}

// Usage
$myDog = new Dog("Rex");
$myDog->eat();    // Inherited from Animal → "Rex is eating."
$myDog->sleep();  // Inherited from Animal → "Rex is sleeping."
$myDog->bark();   // Dog's own method     → "Rex says: Woof!"
```

> **Key Takeaway:** `Dog` did not redefine `eat()` or `sleep()` — it inherited them directly from `Animal`, while still being able to define its own behavior (`bark()`).

---

## 2. The `final` Keyword

### What Does It Do?
The `final` keyword in PHP (and similar languages) acts as a **lock** — it signals that something cannot be changed or extended further.

It can be applied in three contexts:

| Context | Effect |
|---|---|
| `final` **variable** | Not applicable in PHP — use `const` or `define()` for constants instead. |
| `final` **method** | The method cannot be overridden in any subclass. |
| `final` **class** | The class cannot be extended (no subclasses allowed). |

### Example — `final` Method

```php
<?php

class Vehicle {
    final public function startEngine(): void {
        echo "Engine started.\n";
    }
}

class Car extends Vehicle {
    // ERROR: Cannot override a final method
    // public function startEngine(): void { ... }
}

$car = new Car();
$car->startEngine(); // Works — used as-is → "Engine started."
```

### Example — `final` Class

```php
<?php

final class Config {
    public static bool $debug = false;
    public const VERSION = "1.0.0";
}

// ERROR: Class Config is marked as final and cannot be subclassed
// class MyConfig extends Config { }
```

### Why Would a Developer Use This?
- **Security & Integrity:** Prevents other developers from altering critical behavior that must remain consistent (e.g., authentication logic, core algorithms).
- **Immutability:** Encourages predictable, stable behavior — common in utility and configuration classes.
- **Design Enforcement:** Signals a deliberate design decision — "this class/method is complete and should not be modified."

> **Key Takeaway:** `final` is a protective tool. It communicates intent and enforces constraints on how a class or method can be used downstream.

---

## 3. Overriding Methods

### What Does "Override" Mean?
Method overriding occurs when a **child class** provides its own specific implementation for a method that is already defined in its **parent class**. The method in the child class must have the **same name, same parameters, and same return type** as the one in the parent.

This allows the child class to customize or completely replace inherited behavior.

### Example

```java
class Shape {
    void draw() {
        System.out.println("Drawing a generic shape.");
    }
}

class Circle extends Shape {
    @Override
    void draw() {
        System.out.println("Drawing a Circle ○");
    }
}

class Rectangle extends Shape {
    @Override
    void draw() {
        System.out.println("Drawing a Rectangle □");
    }
}
```

> **Note:** The `@Override` annotation is optional but highly recommended — it tells the compiler to verify that you are actually overriding a parent method, catching typos or signature mismatches early.

### Calling the Original Parent Method
If you want to **extend** the parent's behavior rather than completely replace it, you can call the original method using the `super` keyword:

```java
class Circle extends Shape {
    @Override
    void draw() {
        super.draw();  // Calls Shape's draw() first
        System.out.println("Drawing a Circle ○");
    }
}

// Output:
// Drawing a generic shape.
// Drawing a Circle ○
```

> **Key Takeaway:** Overriding customizes inherited behavior. Use `super.methodName()` when you want to keep the parent's logic and build on top of it.

---

## 4. Abstract Class vs. Interface

### Overview
Both **abstract classes** and **interfaces** serve as templates that define a contract for other classes to follow. However, they differ in their flexibility, purpose, and rules.

### Abstract Class
An abstract class is a class that **cannot be instantiated** on its own. It may contain a mix of:
- **Abstract methods** (no body — must be implemented by subclasses)
- **Concrete methods** (with a body — can be inherited as-is)
- **Instance variables / constructors**

```java
abstract class Animal {
    String name;  // Instance variable

    Animal(String name) {   // Constructor
        this.name = name;
    }

    abstract void makeSound();  // Must be implemented by subclass

    void breathe() {            // Concrete method — inherited as-is
        System.out.println(name + " is breathing.");
    }
}

class Cat extends Animal {
    Cat(String name) { super(name); }

    @Override
    void makeSound() {
        System.out.println(name + " says: Meow!");
    }
}
```

### Interface
An interface is a **pure contract** — it defines what a class must do, but traditionally provides no implementation (in older Java). It cannot hold instance variables or constructors. A class **implements** an interface, not extends it.

```java
interface Flyable {
    void fly();  // Abstract by default
}

interface Swimmable {
    void swim();
}

// A class can implement MULTIPLE interfaces
class Duck extends Animal implements Flyable, Swimmable {
    Duck(String name) { super(name); }

    @Override
    void makeSound() { System.out.println("Quack!"); }

    @Override
    public void fly()  { System.out.println(name + " is flying."); }

    @Override
    public void swim() { System.out.println(name + " is swimming."); }
}
```

### Comparison Table

| Feature | Abstract Class | Interface |
|---|---|---|
| Instantiation | ❌ Cannot instantiate | ❌ Cannot instantiate |
| Method types | Abstract + Concrete | Abstract (+ `default`/`static` in Java 8+) |
| Instance variables | ✅ Allowed | ❌ Not allowed (only constants) |
| Constructor | ✅ Allowed | ❌ Not allowed |
| Inheritance | Single (`extends`) | Multiple (`implements`) |
| Best used for | Shared base behavior ("is-a") | Defining capabilities ("can-do") |

### Can a Class Implement Multiple Interfaces?
**Yes.** This is one of the biggest advantages of interfaces over abstract classes. A class can only **extend one parent class**, but it can **implement as many interfaces as needed**.

> **Key Takeaway:** Use an **abstract class** when classes share common state and behavior. Use an **interface** when you want to define a capability that unrelated classes can adopt independently.

---

## 5. Polymorphism

### What Is It?
The word *polymorphism* comes from Greek, meaning **"many forms."** In OOP, it refers to the ability of different objects to respond to the **same method call** in their own unique way.

In practical terms: you can write code that works on a general type, and at runtime, each specific object executes its own version of the method automatically.

### Simple Analogy
Think of a `speak()` command. If you say "speak" to a dog, it barks. Say "speak" to a cat, it meows. Say "speak" to a parrot, it mimics you. The **command is the same**; the **response is different** based on the object.

### Code Example

```java
class Animal {
    void speak() {
        System.out.println("Some generic animal sound...");
    }
}

class Dog extends Animal {
    @Override
    void speak() { System.out.println("Woof! 🐶"); }
}

class Cat extends Animal {
    @Override
    void speak() { System.out.println("Meow! 🐱"); }
}

class Parrot extends Animal {
    @Override
    void speak() { System.out.println("Polly wants a cracker! 🦜"); }
}

// Polymorphism in action
Animal[] animals = { new Dog(), new Cat(), new Parrot() };

for (Animal a : animals) {
    a.speak();  // Each object calls ITS OWN version of speak()
}

// Output:
// Woof! 🐶
// Meow! 🐱
// Polly wants a cracker! 🦜
```

> Notice how the loop uses the `Animal` type for all objects — the correct method is resolved automatically at **runtime**. This is called **runtime polymorphism** (or **dynamic dispatch**).

### Types of Polymorphism

| Type | Also Known As | How It Works |
|---|---|---|
| **Compile-time** | Method Overloading | Same method name, different parameters |
| **Runtime** | Method Overriding | Child class overrides parent method |

### Why Is It Useful?
- Allows writing **generic, flexible code** that works across many types.
- Makes it easy to **add new subclasses** without changing existing logic.
- Core to design patterns and scalable software architecture.

> **Key Takeaway:** Polymorphism lets you treat different objects uniformly through a shared interface, while each object still behaves in its own specific way.

---

## Summary Table

| Concept | One-Line Summary |
|---|---|
| **Inheritance** | Child classes reuse and extend parent class code. |
| **`final` Keyword** | Prevents a class from being extended or a method from being overridden. |
| **Method Overriding** | Child class replaces a parent method with its own version. |
| **Abstract Class vs. Interface** | Abstract = shared base with some code; Interface = pure contract, supports multiple adoption. |
| **Polymorphism** | Same method name, different behavior depending on the object. |

---

*End of Research Notes*