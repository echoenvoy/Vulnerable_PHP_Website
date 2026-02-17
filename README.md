# Vulnerable Web Application (Security Training Lab)

A deliberately vulnerable web application built for practicing web exploitation techniques and understanding real-world security flaws in a safe local environment.

This project simulates common vulnerabilities found in production systems and is designed for ethical hacking training, bug bounty preparation, and OWASP Top 10 practice.

---

## Overview

The lab includes intentionally insecure implementations of:

* SQL Injection
* Cross-Site Scripting (Stored and Reflected)
* Cross-Site Request Forgery (CSRF)
* Authentication Bypass and Brute Force
* File Upload Remote Code Execution
* Directory Traversal
* Command Injection
* Server-Side Template Injection (SSTI)
* Insecure Direct Object Reference (IDOR)
* Broken Authorization
* Race Conditions
* Weak Password Reset
* Insecure JWT handling

The goal is to understand how these vulnerabilities work and how they are exploited in real applications.

---

## Tech Stack

* PHP
* MySQL / MariaDB
* HTML
* Basic session and JWT handling
* Local server environment

---

## Key Learning Objectives

* Practice manual testing using tools like Burp Suite or OWASP ZAP
* Perform automated testing with tools such as SQLMap and Hydra
* Understand insecure authentication and session management
* Explore file upload and command execution vulnerabilities
* Analyze broken access control and logic flaws
* Experiment with JWT manipulation and race conditions

---

## Local Setup

```bash
git clone https://github.com/yourname/vuln-lab.git
cd vuln-lab
```

Import the database and configure credentials in the config file.

Run locally:

```bash
php -S localhost:8000
```

Access via:

```
http://localhost:8000
```

---

## Educational Purpose

This application is intended strictly for:

* Security training
* Educational demonstrations
* Local lab experimentation

It should never be deployed to a public server.

---

This project demonstrates knowledge of web security concepts, common attack vectors, exploitation methodology, and vulnerability research practices.
