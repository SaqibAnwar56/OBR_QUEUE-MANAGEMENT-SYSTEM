<div align="center">

📋 OBR Queue Management System

OBR QMS — Digital Queue & Customer Management Platform

<p>
  <strong>A PHP & MySQL based web application for modern, organized and efficient queue management.</strong>
</p>

<p>
  <a href="https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php">
    <img src="https://img.shields.io/badge/🌐%20Customer%20Portal-LIVE-16a085?style=for-the-badge" alt="Customer Portal">
  </a>
  <a href="https://obr-queue-management.infinityfreeapp.com/OBR_QMS/admin/login.php">
    <img src="https://img.shields.io/badge/🔐%20Admin%20Portal-LIVE-c8a96e?style=for-the-badge" alt="Admin Portal">
  </a>
</p>

<br>








<br>







</div>

📖 Table of Contents

🎯 Project Overview

💡 Problem Statement

🎯 Project Objectives

✨ Key Features

🌐 Live Portals

🏗️ System Architecture

🔄 Application Flow

👥 User Roles

🌐 Customer Portal

🔐 Admin Portal

🗄️ Database Layer

🔌 Backend Architecture

🎨 Frontend Architecture

📁 Project Structure

🧩 Core Modules

🔑 Authentication

💬 Feedback Management

🔁 CRUD Operations

🛡️ Security

📱 Responsive Design

🛠️ Technology Stack

💻 Development Environment

🚀 Local Setup

🗃️ Database Setup

⚙️ Configuration

☁️ Deployment

🌍 Production Configuration

🧪 Testing

🐛 Troubleshooting

🔒 Deployment Security

📊 System Benefits

📈 Future Enhancements

👨‍💻 Development Workflow

📚 Learning Outcomes

📌 Project Status

👨‍💻 Developer

📄 License

🎯 Project Overview

OBR Queue Management System (OBR QMS) is a web-based queue
management application developed to organize customer interaction,
queue-related operations, administrative monitoring, and customer
feedback through a centralized digital platform.

The system is divided into two major areas:

Area

Purpose

🌐 Customer Portal

Public-facing customer interface

🔐 Admin Portal

Protected administrative interface

🗄️ MySQL Database

Centralized application data storage

⚙️ PHP Backend

Server-side processing and database communication

🎨 HTML/CSS/JS

User interface and client-side interaction

The project is designed around a simple client-server architecture
where users interact with the browser, PHP processes requests on the
server, and MySQL stores persistent application data.

💡 Problem Statement

Traditional queue handling can become difficult when customer
information and queue operations are handled manually.

Common problems include:

Manual queue handling

Poor visibility of customer information

Difficulty monitoring customer activity

Difficult feedback management

Repetitive administrative work

Lack of centralized records

Limited accessibility

Increased possibility of human error

OBR QMS addresses these problems by providing a centralized
web-based platform.

🎯 Project Objectives

The major objectives of OBR QMS are:

Digitize queue-related operations.

Provide a dedicated customer portal.

Provide a secure administrator portal.

Store application information in MySQL.

Improve organization of customer records.

Provide customer feedback functionality.

Allow administrators to monitor submitted information.

Reduce dependency on manual record handling.

Separate public and administrative functionality.

Provide a responsive web interface.

Make the application accessible online.

Build an architecture that can be extended in the future.

✨ Key Features

🌐 Customer Features

Public customer interface

Queue-related customer interaction

Customer-facing forms

Feedback submission

Responsive interface

Server-side processing

MySQL-backed data storage

🔐 Administrator Features

Secure admin login

Session-based authentication

Protected dashboard

Database-driven information

Customer feedback viewing

Feedback deletion

Logout functionality

Administrative monitoring

🗄️ Database Features

Centralized MySQL database

Administrator records

Customer feedback records

Timestamped records

PHP MySQLi connectivity

CRUD operations

🎨 UI Features

Modern dark interface

Premium gold accent styling

Responsive layouts

Structured tables

Interactive buttons

Form validation

Clear navigation

🌐 Live Portals

🌐 Customer Portal

Live URL:

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php

Use this portal for customer-facing functionality.

🔐 Admin Portal

Live URL:

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/admin/login.php

Use this portal for administrator authentication and management.

🏗️ System Architecture

The overall architecture can be represented as:

                              ┌─────────────────────┐
                              │        USERS        │
                              └──────────┬──────────┘
                                         │
                         ┌───────────────┴───────────────┐
                         │                               │
                         ▼                               ▼
                ┌────────────────┐              ┌────────────────┐
                │ CUSTOMER       │              │ ADMIN          │
                │ PORTAL         │              │ PORTAL         │
                └───────┬────────┘              └───────┬────────┘
                        │                               │
                        └──────────────┬────────────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │   PHP BACKEND   │
                              └────────┬────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │  MySQL DATABASE │
                              └─────────────────┘

🔄 Application Flow

The general request flow is:

Browser
   │
   ▼
PHP Page
   │
   ▼
Request Processing
   │
   ├───────────────┐
   │               │
   ▼               ▼
Session          Database
Validation       Query
   │               │
   └───────┬───────┘
           │
           ▼
      HTML Response
           │
           ▼
         Browser

👥 User Roles

The application currently separates users into two main roles.

👤 Customer

The customer uses the public portal.

Customer responsibilities include:

Accessing the customer website

Performing available customer operations

Providing feedback

Interacting with customer-facing forms

👨‍💼 Administrator

The administrator uses the protected admin portal.

Administrator responsibilities include:

Logging into the system

Accessing the dashboard

Monitoring records

Viewing customer feedback

Deleting feedback where appropriate

Logging out securely

🌐 Customer Portal

The customer portal is located inside:

OBR_QMS/customer/

Its primary entry point is:

customer/index.php

The customer interface is designed to be directly accessible from
the public website without exposing administrative functionality.

Customer URL

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php

🔐 Admin Portal

The administrator portal is located inside:

OBR_QMS/admin/

Its primary entry point is:

admin/login.php

The administrator section is protected using PHP sessions.

The normal flow is:

admin/login.php
       │
       ▼
Authentication
       │
       ▼
Session Creation
       │
       ▼
dashboard.php
       │
       ├── Feedback
       ├── Management
       └── Other Admin Functions

🗄️ Database Layer

OBR QMS uses MySQL as its relational database.

The application communicates with MySQL through PHP's
MySQLi extension.

The production database is hosted remotely.

Production Database Configuration

Setting

Value

Database Host

sql202.infinityfree.com

Port

3306

Database

if0_42666309_obr_qms

Driver

MySQLi

Character Set

UTF-8 / utf8mb4

⚠️ Never expose the actual production database password in a
public README, GitHub repository, screenshot, or source listing.

🔌 Backend Architecture

The backend is written in PHP.

PHP is responsible for:

Receiving HTTP requests

Processing form submissions

Starting sessions

Authenticating administrators

Communicating with MySQL

Executing SQL queries

Retrieving records

Inserting records

Deleting records

Redirecting users

Generating dynamic HTML

A typical backend request follows:

HTTP Request
     │
     ▼
PHP Script
     │
     ├── Validate Input
     │
     ├── Check Session
     │
     ├── Execute Database Query
     │
     ├── Process Result
     │
     └── Generate Response
             │
             ▼
          Browser

🎨 Frontend Architecture

The frontend uses:

HTML5

CSS3

Vanilla JavaScript

HTML provides structure.

CSS provides:

Layout

Colors

Typography

Responsive behavior

Buttons

Forms

Tables

Animations

JavaScript can provide:

Client-side interaction

Dynamic UI behavior

Form interaction

Confirmation dialogs

Navigation behavior

The project does not require a frontend framework for its core
interface.

📁 Project Structure

A simplified project structure is:

OBR_QMS/
│
├── customer/
│   │
│   ├── index.php
│   ├── db.php
│   ├── test_db.php
│   │
│   ├── css/
│   ├── js/
│   ├── images/
│   └── assets/
│
├── admin/
│   │
│   ├── login.php
│   ├── dashboard.php
│   ├── feedback.php
│   ├── view_feedback.php
│   ├── db.php
│   │
│   ├── css/
│   ├── js/
│   └── assets/
│
├── database/
│   └── obr_qms.sql
│
└── README.md

The exact contents of folders can grow as additional modules are
added to the project.

🧩 Core Modules

Module 1 — Customer Interface

Responsible for customer-facing functionality.

customer/

Module 2 — Customer Database Connection

Responsible for connecting customer-side PHP files to MySQL.

customer/db.php

Module 3 — Administrator Authentication

Responsible for:

Login form

Credential verification

Session creation

Authentication errors

Redirecting authenticated users

admin/login.php

Module 4 — Admin Database Connection

Responsible for connecting administrator PHP pages to MySQL.

admin/db.php

Module 5 — Admin Dashboard

Responsible for providing the administrator's central management
interface.

admin/dashboard.php

Module 6 — Feedback Management

Responsible for displaying customer feedback and allowing
administrators to manage feedback records.

admin/feedback.php
admin/view_feedback.php

🔑 Authentication

The admin portal uses PHP sessions.

After successful authentication, the application stores information
such as:

$_SESSION['admin_id']
$_SESSION['admin_username']

Protected pages verify the session before displaying sensitive
content.

Example:

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

This prevents a user from directly opening protected admin pages
without first authenticating.

🔐 Password Verification

Administrator passwords should be stored as secure hashes.

Password creation:

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

Password verification:

if (password_verify($password, $row['password'])) {
    // Authentication successful
}

This is preferable to storing administrator passwords as plain text.

💬 Feedback Management

The feedback system stores customer-submitted information.

The feedback table can contain fields such as:

Field

Purpose

id

Unique feedback identifier

name

Customer name

email

Customer email

feedback_type

Feedback category

message

Customer feedback

created_at

Submission date/time

The administrator can view feedback through the admin interface.

🗑️ Feedback Deletion

The feedback management page supports deletion of feedback records.

Typical flow:

Admin
  │
  ▼
Feedback Page
  │
  ▼
Select Feedback
  │
  ▼
Delete / Discard
  │
  ▼
MySQL DELETE Query
  │
  ▼
Updated Feedback List

A confirmation dialog should be displayed before destructive actions.

🔁 CRUD Operations

The project follows the standard CRUD concept.

CREATE

Creates a new record.

Example:

Customer submits feedback
        ↓
PHP receives form data
        ↓
INSERT into customer_feedback

READ

Reads records from MySQL.

Example:

Admin opens feedback page
        ↓
SELECT feedback records
        ↓
Display records in table

UPDATE

Modifies an existing record where the relevant module supports
editing.

DELETE

Removes an existing record.

Example:

Admin selects Discard
        ↓
DELETE FROM customer_feedback
        ↓
Refresh feedback page

🛡️ Security

Security is an important part of the system.

Implemented or recommended practices include:

Session-based admin protection

Password hashing

Password verification

Input validation

Output escaping

Prepared statements

Database access through server-side PHP

Separation of customer and admin areas

Example output escaping:

htmlspecialchars($row['message'])

Example prepared statement:

$stmt = $conn->prepare(
    "SELECT id, password
     FROM admins
     WHERE username = ?"
);

$stmt->bind_param("s", $username);

🚨 Important Security Rule

Do not commit production credentials.

Never put values such as:

MySQL password
Hosting password
FTP password
Control-panel password
API secret
Private key

inside a public GitHub repository.

For production, credentials should be stored privately in the hosting
environment or a protected configuration file.

📱 Responsive Design

OBR QMS is intended to work across common screen sizes.

Supported targets include:

┌──────────────────────┐
│      Desktop         │
└──────────────────────┘

┌───────────────┐
│    Tablet     │
└───────────────┘

┌─────────┐
│ Mobile  │
└─────────┘

Responsive design focuses on:

Flexible layouts

Responsive forms

Responsive tables

Mobile-friendly spacing

Readable typography

Accessible buttons

Adaptive navigation

🛠️ Technology Stack

Layer

Technology

Frontend Markup

HTML5

Styling

CSS3

Client-side Logic

JavaScript

Backend

PHP

Database

MySQL

Database Driver

MySQLi

Authentication

PHP Sessions

Web Server

Apache

Local Environment

XAMPP

Database Administration

phpMyAdmin

Production Hosting

InfinityFree

Development Editor

VS Code

💻 Development Environment

Recommended development environment:

Windows

XAMPP

Apache

MySQL

PHP

phpMyAdmin

Visual Studio Code

Modern web browser

The project can be developed locally before being uploaded to
production hosting.

🚀 Local Setup

Step 1 — Install XAMPP

Install XAMPP with:

Apache
MySQL
PHP
phpMyAdmin

Step 2 — Copy the Project

Place the project inside:

C:\xampp\htdocs\OBR_QMS\

Step 3 — Start Services

Open XAMPP Control Panel.

Start:

Apache
MySQL

Step 4 — Open phpMyAdmin

Visit:

http://localhost/phpmyadmin

Step 5 — Create Database

Create:

obr_qms

Step 6 — Import SQL

Import the project database SQL file:

database/obr_qms.sql

Step 7 — Configure Local Database

For XAMPP, a typical local configuration is:

<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "obr_qms";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>

🌐 Local Customer URL

After Apache is running:

http://localhost/OBR_QMS/customer/index.php

🔐 Local Admin URL

http://localhost/OBR_QMS/admin/login.php

🗃️ Database Setup

The database should be imported before testing PHP pages that require
database access.

General process:

SQL File
   │
   ▼
phpMyAdmin
   │
   ▼
MySQL Database
   │
   ▼
PHP db.php
   │
   ▼
Application

⚙️ Configuration

The production database connection is configured through db.php.

The structure is:

$host = "sql202.infinityfree.com";
$port = 3306;
$user = "DATABASE_USERNAME";
$password = "DATABASE_PASSWORD";
$database = "if0_42666309_obr_qms";

Then:

$conn = new mysqli(
    $host,
    $user,
    $password,
    $database,
    $port
);

Finally:

$conn->set_charset("utf8mb4");

☁️ Deployment

The application is currently deployed on InfinityFree.

The production structure is approximately:

htdocs/
│
└── OBR_QMS/
    │
    ├── customer/
    │
    └── admin/

The public domain points to the hosting account.

🌍 Production Configuration

Production database settings use the hosting provider's MySQL
credentials.

Production host:

sql202.infinityfree.com

Production port:

3306

Production database:

if0_42666309_obr_qms

The local development configuration should not be blindly copied to
production.

For example, production should not normally use:

localhost
root
empty password

📤 Deployment Steps

The deployment process is:

1. Develop locally
        ↓
2. Test locally
        ↓
3. Create hosting account
        ↓
4. Create MySQL database
        ↓
5. Upload project
        ↓
6. Upload OBR_QMS folder
        ↓
7. Import SQL database
        ↓
8. Update db.php
        ↓
9. Test PHP
        ↓
10. Test Customer Portal
        ↓
11. Test Admin Portal
        ↓
12. Test database operations
        ↓
13. Go Live

🧪 Testing

Testing should be performed on both portals.

Customer Testing

Check:

Homepage loads.

Customer pages load.

Forms work.

Database-connected features work.

Feedback submission works.

Data is stored correctly.

Mobile layout works.

Desktop layout works.

🔐 Admin Testing

Check:

Login page loads.

Correct credentials work.

Incorrect credentials are rejected.

Empty fields are rejected.

Session is created.

Dashboard opens after authentication.

Protected pages reject unauthenticated access.

Feedback page loads.

Feedback records display.

Delete action works.

Logout works.

🗄️ Database Testing

Check:

Database exists.

Tables exist.

PHP connects successfully.

INSERT operations work.

SELECT operations work.

DELETE operations work.

Timestamps are generated correctly.

Data appears in phpMyAdmin.

🐛 Troubleshooting

Problem: 404 Error

Possible reasons:

Wrong URL

Wrong filename

Wrong folder name

Incorrect capitalization

File uploaded to the wrong directory

Project not inside the correct hosting directory

Remember that hosting paths can be case-sensitive.

For example:

OBR_QMS

and:

obr_qms

may be treated as different paths.

🐛 Database Connection Error

If you see a database connection error, check:

Host
Username
Password
Database name
Port

For production, use the MySQL credentials provided by the hosting
provider.

Do not use the local XAMPP configuration on the live server.

🐛 "No Such File or Directory" MySQL Error

A common cause is using:

localhost

when the production database is hosted remotely.

The production configuration should use the hosting MySQL hostname:

sql202.infinityfree.com

🐛 Admin Login Not Working

Check the following:

1. admins table exists
2. Username exists
3. Password is stored as a hash
4. db.php uses production credentials
5. PHP session is enabled
6. login.php includes db.php
7. dashboard.php checks the session

🐛 Feedback Page Not Working

Check:

1. customer_feedback table exists
2. feedback page uses the correct db.php
3. Database host is correct
4. Database name is correct
5. created_at exists if the page uses it
6. id exists if deletion is enabled
7. Admin session is active

🧭 URL Structure

The deployed project follows this structure:

https://obr-queue-management.infinityfreeapp.com/
│
└── OBR_QMS/
    │
    ├── customer/
    │   └── index.php
    │
    └── admin/
        └── login.php

🔗 Direct Links

🌐 Customer

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php

🔐 Admin

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/admin/login.php

📊 System Benefits

OBR QMS provides several practical benefits:

Centralized data

Reduced manual work

Better organization

Faster information retrieval

Dedicated customer interface

Dedicated admin interface

Digital feedback management

Secure administrator authentication

Online accessibility

Expandable architecture

📈 Future Enhancements

The architecture can be expanded with additional functionality.

Potential future features include:

🎫 Automatic queue number generation

📺 Live queue display

⏱️ Estimated waiting time

🔔 Customer notifications

📱 SMS notifications

📧 Email notifications

🔳 QR-code queue tickets

📊 Admin analytics

📈 Queue statistics

📋 Reports

📄 PDF reports

📊 Excel exports

🔎 Advanced search

🧰 Advanced filtering

👥 Multiple administrator roles

📝 Audit logs

🔐 Two-factor authentication

🌐 REST API

📱 Progressive Web App

☁️ Improved cloud deployment

👨‍💻 Development Workflow

The project development process can be summarized as:

Requirement Analysis
        │
        ▼
System Planning
        │
        ▼
Architecture Design
        │
        ▼
Database Design
        │
        ▼
Frontend Development
        │
        ▼
Backend Development
        │
        ▼
Authentication
        │
        ▼
Database Integration
        │
        ▼
CRUD Development
        │
        ▼
Testing
        │
        ▼
Bug Fixing
        │
        ▼
Deployment
        │
        ▼
Live Testing
        │
        ▼
Maintenance

🧠 Development Principles

The project follows several practical development principles:

Separation of Concerns

Customer and administrator functionality are kept in separate
directories.

Reusable Database Connection

Database connection logic is kept inside db.php rather than
duplicated unnecessarily across pages.

Session Protection

Administrative pages use sessions to control access.

Server-Side Processing

Sensitive database operations are performed by PHP on the server.

Database-Driven Architecture

Persistent records are stored in MySQL rather than depending only on
browser-side storage.

🧩 Why PHP + MySQL?

PHP and MySQL provide a practical stack for this type of application.

PHP provides:

Server-side processing

Form handling

Session management

Database integration

Dynamic page generation

MySQL provides:

Structured storage

Relational data

SQL queries

Persistent records

CRUD functionality

Together they provide the foundation for a complete dynamic web
application.

📦 Deployment Architecture

The production architecture is:

                       INTERNET
                           │
                           ▼
              ┌───────────────────────┐
              │     InfinityFree      │
              │       Hosting         │
              └───────────┬───────────┘
                          │
             ┌────────────┴────────────┐
             │                         │
             ▼                         ▼
       Customer PHP               Admin PHP
             │                         │
             └────────────┬────────────┘
                          │
                          ▼
                   Remote MySQL
                          │
                          ▼
                Application Database

🧪 Quality Checklist

Before considering a deployment complete:

[✓] Customer URL opens
[✓] Admin URL opens
[✓] PHP is working
[✓] Database is created
[✓] Database connection works
[✓] Customer interface works
[✓] Admin login works
[✓] Session protection works
[✓] Dashboard works
[✓] Feedback page works
[✓] Feedback data is stored
[✓] Feedback data is displayed
[✓] Delete operation works
[✓] Logout works
[✓] Responsive layout checked
[✓] Production URLs checked

📚 Learning Outcomes

Development of OBR QMS provides practical experience in:

Web application development

PHP programming

MySQL database management

SQL queries

CRUD operations

Authentication

Session management

Form processing

Server-side programming

HTML5

CSS3

JavaScript

Responsive web design

Database connectivity

Web hosting

Deployment

Debugging

Project architecture

🎓 Academic / Portfolio Value

OBR QMS demonstrates the development of a complete
database-driven web application.

The project can demonstrate knowledge of:

Frontend
   +
Backend
   +
Database
   +
Authentication
   +
CRUD
   +
Deployment

This makes the project suitable for:

Academic demonstration

Software engineering coursework

Portfolio presentation

Web development practice

PHP/MySQL learning

Database project demonstration

🏁 Project Status

<div align="center">

🚀 LIVE & DEPLOYED

Customer Portal

Open Customer Website

Admin Portal

Open Admin Website

</div>

📌 Important Notes

The customer and admin portals are separate URLs.

The admin portal should not be treated as a public customer page.

Both portals can communicate with the same MySQL database.

Production db.php must contain valid hosting database settings.

Database credentials must remain private.

SQL files should be imported into the correct database.

File and folder names must match their URLs exactly.

PHP pages require a PHP-capable web server.

MySQL-connected pages require a working database connection.

Protected admin pages require a valid admin session.

🔒 Production Security Checklist

Before publishing a production version:

[ ] Remove test/debug pages
[ ] Remove exposed passwords
[ ] Do not publish database credentials
[ ] Use password hashes
[ ] Validate form input
[ ] Use prepared SQL statements
[ ] Escape database output
[ ] Protect admin routes
[ ] Test logout
[ ] Review file permissions
[ ] Review database permissions
[ ] Remove unnecessary sensitive files

🤝 Contribution

For future development:

Create a new branch.

Implement the feature.

Test locally.

Verify database operations.

Test customer and admin flows.

Review security implications.

Commit the changes.

Deploy only after successful testing.

📝 Versioning

A simple versioning approach can be used:

v1.0.0
│
├── 1 = Major release
├── 0 = Minor release
└── 0 = Patch

Example future releases:

v1.0.0  Initial deployed system
v1.1.0  Additional queue functionality
v1.2.0  Improved admin analytics
v2.0.0  Major architecture enhancement

📄 License

This project is developed as a software engineering project by
Saqib Anwar.

Unless otherwise specified, the project source code, interface
design, documentation, and custom implementation should be treated
as project-owned work.

Third-party libraries, services, hosting platforms, icons, fonts,
or other external resources remain subject to their respective
licenses and terms.

👨‍💻 Developer

<div align="center">

Saqib Anwar

Software Engineering Student | Web Developer | PHP & MySQL Developer

OBR Queue Management System

</div>

⭐ Final Summary

OBR Queue Management System is a complete web-based application
built around a simple and expandable architecture.

The system separates:

CUSTOMER
   │
   ▼
Customer Portal
   │
   ▼
PHP Backend
   │
   ▼
MySQL
   ▲
   │
PHP Backend
   ▲
   │
Admin Portal
   ▲
   │
ADMIN

The customer portal provides the public-facing experience, while
the admin portal provides protected management functionality.

The application uses PHP for server-side logic, MySQL for persistent
data storage, HTML/CSS/JavaScript for the interface, PHP sessions
for administrator authentication, and InfinityFree for live
deployment.

<div align="center">

🚀 OBR QMS

Organize • Manage • Improve

Customer Portal:
https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php

Admin Portal:
https://obr-queue-management.infinityfreeapp.com/OBR_QMS/admin/login.php

<br>

Built with 💻 by Saqib Anwar

</div
