# Flash Message PHP Class
## Manage Notifications, Errors, and Form Values with Sessions

A lightweight PHP utility for managing flash messages, form validation errors, and posted form values using PHP sessions.

Flash messages are temporary notifications (e.g., success, warnings, errors) displayed to users after an action, and are automatically cleared after a page reload. This class also supports form validation error handling and repopulating form fields with previously submitted values.

---

## Features
- **Flash messages:** Supports multiple message types (`success`, `danger`, `warning`, `info`).
- **Validation errors:** Easily manage and display form validation errors.
- **Form repopulation:** Automatically sanitize and repopulate submitted form values.
- **Session-based:** Leverages PHP sessions for temporary storage and clears data after being displayed.
- **XSS protection:** Sanitizes input values to prevent cross-site scripting (XSS).

---

## Installation

1. Clone or download this repository.
2. Include the `FlashMessage` class in your PHP project.

```php
require_once 'FlashMessage.php';
```

---

## Usage

### **1. Setting Flash Messages**

You can set success, warning, danger, or info messages.

```php
$flash = new FlashMessage();

$flash->set([
    ['text' => 'Account created successfully!', 'type' => FlashMessage::SUCCESS]
]);

$data = $flash->display();
foreach ($data['messages'] as $message) {
    echo "<div class='alert alert-{$message['type']}'>{$message['text']}</div>";
}
```

**Output:**
```html
<div class="alert alert-success">Account created successfully!</div>
```

---

### **2. Handling Form Validation Errors**

Store validation errors and repopulate form inputs.

```php
$flash = new FlashMessage();

$flash->set(
    null, 
    ['username' => 'Username is required', 'email' => 'Invalid email address'], 
    ['username' => 'john_doe', 'email' => 'invalid-email']
);

$data = $flash->display();

// Display errors
foreach ($data['errors'] as $field => $error) {
    echo "<div class='error'>{$field}: {$error}</div>";
}

// Repopulate form
echo "<form>";
echo "<input type='text' name='username' value='{$data['values']['username']}' />";
echo "<input type='text' name='email' value='{$data['values']['email']}' />";
echo "</form>";
```

**Output:**
```html
<div class="error">username: Username is required</div>
<div class="error">email: Invalid email address</div>
<form>
    <input type="text" name="username" value="john_doe" />
    <input type="text" name="email" value="invalid-email" />
</form>
```

---

### **3. Displaying Mixed Notifications**

Set and display multiple types of messages in a single request.

```php
$flash = new FlashMessage();

$flash->set([
    ['text' => 'Welcome back!', 'type' => FlashMessage::INFO],
    ['text' => 'Profile updated successfully.', 'type' => FlashMessage::SUCCESS],
    ['text' => 'Failed to update password.', 'type' => FlashMessage::DANGER],
    ['text' => 'Verify your email address.', 'type' => FlashMessage::WARNING]
]);

$data = $flash->display();

foreach ($data['messages'] as $message) {
    echo "<div class='alert alert-{$message['type']}'>{$message['text']}</div>";
}
```

**Output:**
```html
<div class="alert alert-info">Welcome back!</div>
<div class="alert alert-success">Profile updated successfully.</div>
<div class="alert alert-danger">Failed to update password.</div>
<div class="alert alert-warning">Verify your email address.</div>
```

---

### **4. Clearing and Reusing Flash Data**

The class automatically clears session data after being displayed, but you can set new data for the next request.

```php
$flash = new FlashMessage();

// Set initial message
$flash->set([
    ['text' => 'Initial notification.', 'type' => FlashMessage::INFO]
]);

$data = $flash->display();
foreach ($data['messages'] as $message) {
    echo "<div class='alert alert-{$message['type']}'>{$message['text']}</div>";
}

// Set new data for the next page
$flash->set([
    ['text' => 'Message for the next request.', 'type' => FlashMessage::SUCCESS]
]);
```

**First Page Output:**
```html
<div class="alert alert-info">Initial notification.</div>
```

**Next Page Output:**
```html
<div class="alert alert-success">Message for the next request.</div>
```

---

### **5. Preventing Cross-Site Scripting (XSS)**

The class sanitizes all posted values using `htmlspecialchars` to prevent malicious input.

```php
$flash = new FlashMessage();

$flash->set(null, null, ['comment' => '<script>alert("Hacked!")</script>']);

$data = $flash->display();
echo "<textarea name='comment'>{$data['values']['comment']}</textarea>";
```

**Output:**
```html
<textarea name="comment">&lt;script&gt;alert(&quot;Hacked!&quot;)&lt;/script&gt;</textarea>
```

---

## License

This project is licensed under the MIT License. Feel free to use and modify it as needed.

## Author
Created by [IdeaGlory](https://ideaglory.com).
