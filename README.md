<div align="center">

# 📋 OBR Queue Management System

**OBR QMS — Digital Queue & Customer Management Platform**

<img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
<img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
<img src="https://img.shields.io/badge/HTML5-Frontend-E34F26?style=for-the-badge&logo=html5&logoColor=white" alt="HTML5">
<img src="https://img.shields.io/badge/CSS3-Styling-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3">
<img src="https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
<img src="https://img.shields.io/badge/Hosting-InfinityFree-0A66C2?style=for-the-badge" alt="InfinityFree">

### 🌐 [Customer Portal](https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php)
### 🔐 [Admin Portal](https://obr-queue-management.infinityfreeapp.com/OBR_QMS/admin/login.php)

**Developed by Saqib Anwar**

</div>

---

## 📌 About

OBR Queue Management System (OBR QMS) is a PHP/MySQL web application for digital queue management, customer interaction, feedback collection, and administrative monitoring.

The system is divided into two interfaces:

| Portal | Purpose |
|---|---|
| 🌐 Customer | Queue-related operations and feedback |
| 🔐 Admin | Authentication, dashboard, and record management |

## 🎯 Objectives

- Digitalize manual queue handling
- Provide a simple, accessible customer interface
- Centralize records in a MySQL database
- Provide a protected administration layer
- Collect and manage customer feedback
- Deploy a practical, responsive web application

## ✨ Features

**🌐 Customer**
- Customer-facing website
- Queue-related operations
- Feedback submission
- Database-connected forms
- Responsive interface
- Server-side PHP processing

**🔐 Admin**
- Secure admin login
- Session-based authentication
- Protected dashboard
- Customer feedback management
- Feedback deletion
- Database-driven records
- Logout functionality

## 🏗️ Architecture

```
                    USERS
                      │
          ┌───────────┴───────────┐
          ▼                       ▼
   CUSTOMER PORTAL           ADMIN PORTAL
    /customer/                 /admin/
          │                       │
          └───────────┬───────────┘
                       ▼
                  PHP BACKEND
                       │
                       ▼
                 MySQL DATABASE
```

**Application Layers**

```
HTML + CSS + JavaScript
        ↓
       PHP
        ↓
      MySQL
```

**Customer Flow**

```
Customer → Website → PHP → MySQL → Result
```

**Admin Flow**

```
Admin → Login → admins Table → Session → Dashboard → Manage Records
```

## 🌐 Customer Portal

The customer portal is the public-facing part of OBR QMS.

**Live URL:** https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php

Typical flow:

```
Customer → Customer Interface → Form / Queue Operation → PHP Processing → MySQL
```

## 🔐 Admin Portal

The admin portal is protected using PHP sessions.

**Live URL:** https://obr-queue-management.infinityfreeapp.com/OBR_QMS/admin/login.php

**Authentication Flow**

```
Username + Password
        ↓
  PHP Validation
        ↓
   admins Table
        ↓
 password_verify()
        ↓
   PHP Session
        ↓
  dashboard.php
```

Example protection applied to admin pages:

```php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
```

## 🗄️ Database

The deployed application uses a MySQL database: `if0_42666309_obr_qms`

Main project tables:

```
OBR_QMS
 ├── admins
 └── customer_feedback
```

**Feedback Data**

The feedback module stores:
- ID
- Customer name
- Email
- Feedback type
- Message
- Created date/time

**Feedback Flow**

```
Customer → Feedback Form → feedback.php → customer_feedback table → Admin Dashboard → view_feedback.php
```

## 📁 Project Structure

```
OBR_QMS/
│
├── customer/
│   ├── index.php
│   ├── db.php
│   ├── feedback.php
│   ├── test_db.php
│   └── assets/
│
├── admin/
│   ├── login.php
│   ├── db.php
│   ├── dashboard.php
│   ├── view_feedback.php
│   └── other admin pages
│
└── README.md
```

> ⚠️ Hosting is case-sensitive. Keep file and folder names exactly consistent.

## 🔌 Database Connection

Hosted PHP files use the InfinityFree MySQL server:

```php
<?php
$host     = "sql202.infinityfree.com";
$port     = 3306;
$user     = "YOUR_MYSQL_USERNAME";
$password = "YOUR_MYSQL_PASSWORD";
$database = "if0_42666309_obr_qms";

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
```

> 🔒 Never publish your real database password on GitHub. Use environment variables or a `.env` file excluded via `.gitignore` for production credentials.

## 🛡️ Security

The application follows these basic web security practices:

- PHP sessions for admin authentication
- Protected admin pages
- Password hashing with `password_verify()`
- Prepared statements for login queries
- `htmlspecialchars()` applied to displayed user data
- Server-side validation
- Separate customer/admin areas

Example:

```php
if (password_verify($password, $row['password'])) {
    $_SESSION['admin_id'] = $row['id'];
}
```

## 🎨 UI / UX

The system focuses on:
- Professional visual design
- Clear navigation
- Responsive layouts
- Consistent typography
- Accessible forms
- Customer-friendly pages
- Premium dark/gold admin interface
- Clear error and action messages

Responsive target: 📱 Mobile → 📲 Tablet → 💻 Laptop → 🖥️ Desktop

## 🛠️ Technology Stack

| Technology | Role |
|---|---|
| PHP | Backend / server-side logic |
| MySQL | Persistent database |
| HTML5 | Page structure |
| CSS3 | Styling / responsive UI |
| JavaScript | Client-side interaction |
| Apache | Web server |
| phpMyAdmin | Database administration |
| InfinityFree | Hosting |

## 💻 Local Development

**Requirements**
- XAMPP
- Apache
- MySQL
- PHP
- Web browser
- Code editor

**Setup**
1. Install XAMPP.
2. Start Apache and MySQL.
3. Copy `OBR_QMS` into `htdocs`.
4. Create/import the MySQL database.
5. Configure `db.php`.
6. Open the customer portal.
7. Test the admin portal.

**Local URLs**
- Customer: `http://localhost/OBR_QMS/customer/index.php`
- Admin: `http://localhost/OBR_QMS/admin/login.php`

## ☁️ Deployment

The current project is deployed on InfinityFree.

```
Local Project → Project Files → InfinityFree File Manager → htdocs/OBR_QMS/ → MySQL Database → Live PHP Application
```

**Hosted Structure**

```
htdocs/
└── OBR_QMS/
    ├── customer/
    └── admin/
```

**Database Deployment**
1. Create the MySQL database from the hosting panel.
2. Select the assigned database in phpMyAdmin.
3. Import the SQL tables.
4. Do **not** run `CREATE DATABASE` when the host has already created it.
5. Update `db.php` in both `customer/` and `admin/`.
6. Test the connection.
7. Test both live portals.

## 🧪 Testing Checklist

**Customer**
- [ ] Homepage opens
- [ ] Customer pages load
- [ ] Forms work
- [ ] Feedback submits
- [ ] Database records are created
- [ ] Mobile layout works

**Admin**
- [ ] Login opens
- [ ] Valid credentials work
- [ ] Invalid credentials show an error
- [ ] Dashboard opens
- [ ] Protected pages require login
- [ ] Feedback appears
- [ ] Feedback deletion works
- [ ] Logout works

**Database**
- [ ] Correct hostname
- [ ] Correct username
- [ ] Correct password
- [ ] Correct database name
- [ ] Required tables exist
- [ ] PHP connects successfully

## 🐛 Troubleshooting

**404 Error** — check:
- File name
- Folder name
- Capitalization
- File location
- URL path

**Database Error** — check:
- Hostname
- Username
- Password
- Database name
- Port

> Hosted PHP should not normally use `localhost` / `root` for the online database.

**Feedback Page Error** — make sure the page uses the hosted connection:

```php
require_once 'db.php';
```

and does **not** contain an old local connection such as:

```php
new mysqli("localhost", "root", "", "obr_qms");
```

**Admin Login Works but Dashboard Fails** — check:
- Session creation
- `$_SESSION['admin_id']`
- Dashboard session protection
- Database/table names
- Correct `db.php`

## 🔄 Development Workflow

```
Requirements → UI Design → HTML/CSS → JavaScript → PHP Backend →
MySQL Database → Local Testing → Debugging → Hosting Configuration →
Deployment → Live Testing
```

## 🚀 Future Enhancements

- 🎫 Automatic queue ticket generation
- 📺 Live queue display
- 🔔 Customer notifications
- 📊 Admin analytics
- 👥 Multiple admin roles
- 🔎 Advanced search/filtering
- 📅 Queue history
- 📤 PDF/Excel reports
- 🔐 Stronger authentication
- ⚡ AJAX live queue updates
- 📱 Progressive Web App support

## 🎓 Project Skills Demonstrated

```
PHP
 ├── Sessions
 ├── Forms
 ├── Authentication
 ├── CRUD
 └── Server-side processing

MySQL
 ├── Tables
 ├── Queries
 ├── Relationships
 └── Persistent storage

Frontend
 ├── HTML5
 ├── CSS3
 ├── Responsive Design
 └── JavaScript

Deployment
 ├── InfinityFree
 ├── phpMyAdmin
 ├── File Manager
 └── Production debugging
```

## 👨‍💻 Developer

**Saqib Anwar**
[GitHub](https://github.com/SaqibAnwar56) · [LinkedIn](https://www.linkedin.com/in/saqib-anwar-673097351)

## 📄 License

This project was developed for educational and portfolio purposes. Feel free to explore the code — please reach out before reusing it commercially.

---

<div align="center">

**📋 OBR QMS**
Digital Queue Management • Customer Interaction • Administration

Built with 💻 PHP + 🗄️ MySQL + 🎨 HTML/CSS/JavaScript

⭐ OBR Queue Management System ⭐

</div>
