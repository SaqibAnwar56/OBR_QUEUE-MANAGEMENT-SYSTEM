OBR QUEUE MANAGEMENT SYSTEM
============================

Project Name:
OBR Queue Management System (OBR QMS)

Project Type:
Web-Based Queue Management System

Developer:
Saqib Anwar

Technology:
PHP, MySQL, HTML5, CSS3, JavaScript

Hosting:
InfinityFree


1. PROJECT OVERVIEW
===================

OBR Queue Management System is a web-based application
developed to manage customers and queues digitally.

The system has two main interfaces:

1. Customer Website
2. Admin Website

The Customer Website is the public interface where customers
interact with the queue management system.

The Admin Website is a protected management area where an
administrator can monitor and manage system information.

Basic architecture:

Browser
   |
   v
PHP Application
   |
   v
MySQL Database


2. LIVE WEBSITES
================

CUSTOMER WEBSITE:

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php


ADMIN WEBSITE:

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/admin/login.php


3. PROJECT OBJECTIVES
=====================

- Digitize queue management.
- Reduce manual queue handling.
- Improve customer experience.
- Organize customer information.
- Allow administrators to manage the system.
- Store information in a centralized MySQL database.
- Provide customer feedback functionality.
- Provide a protected administrator dashboard.
- Make the system accessible through a web browser.
- Deploy the application online.


4. SYSTEM ARCHITECTURE
======================

                    USERS
                      |
             +--------+--------+
             |                 |
             v                 v
       CUSTOMER PORTAL     ADMIN PORTAL
             |                 |
             +--------+--------+
                      |
                      v
                PHP BACKEND
                      |
                      v
               MySQL DATABASE


CUSTOMER FLOW:

Customer
   |
   v
customer/index.php
   |
   v
PHP Processing
   |
   v
MySQL Database


ADMIN FLOW:

Administrator
   |
   v
admin/login.php
   |
   v
Authentication
   |
   v
admin/dashboard.php
   |
   v
Admin Management Pages
   |
   v
MySQL Database


5. MAIN MODULES
===============

CUSTOMER MODULE
---------------

Location:

OBR_QMS/customer/

Main entry point:

customer/index.php

The customer module provides the public-facing interface
of the queue management system.

Functions include customer-facing queue operations,
customer information and feedback functionality.


ADMIN MODULE
------------

Location:

OBR_QMS/admin/

Main entry point:

admin/login.php

Important files:

- login.php
- dashboard.php
- feedback.php
- view_feedback.php
- db.php

The admin module provides protected management functionality.


DATABASE MODULE
---------------

The application uses MySQL.

PHP communicates with MySQL through the MySQLi extension.


6. TECHNOLOGY STACK
===================

Frontend:
- HTML5
- CSS3
- JavaScript

Backend:
- PHP

Database:
- MySQL / MariaDB compatible

Database Connection:
- PHP MySQLi

Authentication:
- PHP Sessions

Password Security:
- password_hash()
- password_verify()

Web Server:
- Apache / PHP compatible server

Hosting:
- InfinityFree


7. DATABASE CONFIGURATION
=========================

Production database:

Host:
sql202.infinityfree.com

Port:
3306

Database:
if0_42666309_obr_qms

The application connects through db.php.

Example production connection:

<?php

$host = "sql202.infinityfree.com";
$port = 3306;
$user = "YOUR_DATABASE_USERNAME";
$password = "YOUR_DATABASE_PASSWORD";
$database = "if0_42666309_obr_qms";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $database,
    $port
);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>

IMPORTANT:
Never publish the real database password in GitHub,
README files, screenshots, or public source code.


8. DATABASE TABLES
==================

Important application tables include:

admins
customer_feedback

Additional queue, customer and appointment tables can be
included according to the final SQL database structure.


ADMINS
------

Purpose:
Stores administrator login information.

Typical fields:

- id
- username
- password


CUSTOMER_FEEDBACK
-----------------

Purpose:
Stores customer feedback.

Typical fields:

- id
- name
- email
- feedback_type
- message
- created_at


9. ADMIN AUTHENTICATION
=======================

The administrator login system uses PHP sessions.

After successful login the system stores:

admin_id
admin_username

Protected pages verify the session.

Example:

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

This prevents unauthorized users from opening protected
administrator pages.


10. PASSWORD SECURITY
=====================

Administrator passwords should never be stored as plain text.

Create a password hash:

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

Verify a password:

password_verify($password, $storedHash);

The login system checks the entered password against the
stored password hash.


11. CUSTOMER FEEDBACK SYSTEM
============================

Customer feedback is stored in:

customer_feedback

Feedback can contain:

- Customer name
- Customer email
- Feedback type
- Customer message
- Date and time

Administrators can view submitted feedback and remove
feedback records when required.

Database operations involving user input should use
prepared statements.


12. CRUD OPERATIONS
===================

CREATE
------
Add new customer, queue or feedback information.

READ
----
Retrieve and display stored information.

UPDATE
------
Modify existing records.

DELETE
------
Remove records such as feedback.

These operations form the basic CRUD functionality of OBR QMS.


13. PROJECT FOLDER STRUCTURE
============================

OBR_QMS/
|
|-- customer/
|   |
|   |-- index.php
|   |-- db.php
|   |-- test_db.php
|   |-- css/
|   |-- js/
|   |-- images/
|   `-- assets/
|
|-- admin/
|   |
|   |-- login.php
|   |-- dashboard.php
|   |-- feedback.php
|   |-- view_feedback.php
|   |-- db.php
|   |-- css/
|   |-- js/
|   `-- assets/
|
|-- database/
|   `-- obr_qms.sql
|
`-- README.txt


14. HOW THE CUSTOMER WEBSITE WORKS
==================================

1. Customer opens the customer URL.
2. Browser requests the PHP page.
3. PHP loads required files.
4. PHP connects to MySQL when database information is needed.
5. PHP processes the request.
6. Server returns the generated page.
7. Customer interacts with the system.

Customer URL:

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php


15. HOW THE ADMIN WEBSITE WORKS
===============================

1. Administrator opens admin/login.php.
2. Login form is displayed.
3. Administrator enters username and password.
4. PHP receives the POST request.
5. PHP searches the admins table.
6. password_verify() checks the password.
7. Successful authentication creates a PHP session.
8. Administrator is redirected to dashboard.php.
9. Protected admin pages become accessible.
10. Logout destroys the session.

Admin URL:

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/admin/login.php


16. LOCAL DEVELOPMENT
=====================

Recommended local environment:

- XAMPP
- Apache
- MySQL
- PHP
- phpMyAdmin
- VS Code
- Web browser

Place the project inside:

C:/xampp/htdocs/OBR_QMS/

Start Apache and MySQL from XAMPP.

Open:

http://localhost/phpmyadmin

Create database:

obr_qms

Import:

database/obr_qms.sql


17. LOCAL DATABASE CONNECTION
=============================

For XAMPP:

Host:
localhost

Username:
root

Password:
empty by default

Database:
obr_qms

Example:

<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "obr_qms";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>

For production, use the hosting provider's MySQL credentials.


18. DEPLOYMENT
==============

The project is deployed on InfinityFree.

Domain:

https://obr-queue-management.infinityfreeapp.com/

Project location:

htdocs/OBR_QMS/

Structure:

htdocs/
|
`-- OBR_QMS/
    |
    |-- customer/
    |
    `-- admin/


19. DEPLOYMENT STEPS
====================

1. Create hosting account.
2. Create website/domain.
3. Create MySQL database.
4. Upload OBR_QMS to the hosting server.
5. Place it inside htdocs/OBR_QMS/.
6. Import the SQL database using phpMyAdmin.
7. Update db.php with production database details.
8. Open the customer website.
9. Test customer functionality.
10. Open the admin website.
11. Test administrator login.
12. Test dashboard and database operations.


20. IMPORTANT FILE PATHS
========================

Customer:

OBR_QMS/customer/index.php

Customer database:

OBR_QMS/customer/db.php

Admin login:

OBR_QMS/admin/login.php

Admin dashboard:

OBR_QMS/admin/dashboard.php

Admin feedback:

OBR_QMS/admin/feedback.php

or:

OBR_QMS/admin/view_feedback.php

Admin database:

OBR_QMS/admin/db.php

SQL database:

OBR_QMS/database/obr_qms.sql


21. COMMON DEPLOYMENT PROBLEMS
==============================

404 ERROR
---------

Check:

- Correct folder name
- Correct file name
- Correct capitalization
- Correct upload location
- Correct URL

The hosting server is case-sensitive.

For example:

OBR_QMS

is different from:

obr_qms


DATABASE ERROR
--------------

Check:

- MySQL hostname
- Username
- Password
- Database name
- Port

For InfinityFree production, do not use:

localhost
root

Use the MySQL connection details supplied by the host.


22. SECURITY
============

Important practices:

1. Never publish database passwords.
2. Never publish administrator passwords.
3. Use password_hash().
4. Use password_verify().
5. Use prepared SQL statements.
6. Validate user input.
7. Escape displayed database values.
8. Protect admin pages with sessions.
9. Keep sensitive configuration private.
10. Change any exposed production password.

Example output escaping:

htmlspecialchars($row['message'])

Example prepared statement:

$stmt = $conn->prepare(
    "SELECT id, password FROM admins WHERE username = ?"
);

$stmt->bind_param("s", $username);


23. TESTING
===========

CUSTOMER TESTING
----------------

- Open customer homepage.
- Test queue functionality.
- Test customer forms.
- Submit feedback.
- Verify database insertion.
- Test responsive design.


ADMIN TESTING
-------------

- Open admin login.
- Test valid credentials.
- Test invalid credentials.
- Verify session creation.
- Open dashboard.
- Open feedback management.
- Test feedback deletion.
- Test logout.
- Test unauthorized access.


DATABASE TESTING
----------------

Verify:

- Database connection.
- Tables exist.
- Records are inserted.
- Records are retrieved.
- Records are updated.
- Records are deleted.
- Relationships work where applicable.


24. USER ROLES
==============

CUSTOMER
--------

Uses the public customer portal.

Path:

/customer/


ADMINISTRATOR
-------------

Uses the protected administration portal.

Path:

/admin/

The two interfaces are separated for better organization
and access control.


25. DESIGN APPROACH
===================

The project uses a modern web interface.

The admin interface uses a premium dark and gold visual style.

Design principles:

- Clean interface
- Simple navigation
- Responsive layouts
- Clear forms
- Readable tables
- Consistent colors
- User-friendly interactions
- Protected administration


26. RESPONSIVE DESIGN
=====================

The system is designed for:

- Desktop
- Laptop
- Tablet
- Mobile phone

Responsive CSS can adjust:

- Layout
- Forms
- Tables
- Buttons
- Navigation
- Typography
- Spacing


27. DEVELOPMENT WORKFLOW
========================

1. Gather system requirements.
2. Identify customer and admin roles.
3. Design system architecture.
4. Design database tables.
5. Create MySQL database.
6. Develop customer interface.
7. Develop admin authentication.
8. Develop admin dashboard.
9. Connect PHP with MySQL.
10. Implement CRUD operations.
11. Implement feedback management.
12. Test locally.
13. Fix errors.
14. Deploy to hosting.
15. Configure production database.
16. Test live website.
17. Maintain and improve the application.


28. PROJECT FLOW
===============

                    START
                      |
                      v
             Open OBR QMS Website
                      |
             +--------+--------+
             |                 |
             v                 v
        CUSTOMER            ADMIN
             |                 |
             v                 v
       Customer Portal     Login Page
                               |
                         Authentication
                               |
                    +----------+----------+
                    |                     |
                  FAIL                  SUCCESS
                    |                     |
                    v                     v
              Login Again            Dashboard
                                          |
                              +-----------+-----------+
                              |           |           |
                              v           v           v
                            Queue      Feedback    Other Admin
                              |           |           |
                              +-----------+-----------+
                                          |
                                          v
                                      MySQL DB


29. PROJECT BENEFITS
====================

- Digital queue management
- Centralized data storage
- Better customer experience
- Reduced manual work
- Administrative control
- Feedback management
- Online accessibility
- Database-driven architecture
- Expandable system structure


30. FUTURE ENHANCEMENTS
=======================

Possible future improvements:

- Real-time queue display
- Automatic queue number generation
- SMS notifications
- Email notifications
- QR-code queue tickets
- Live waiting-time estimation
- Customer accounts
- Admin analytics
- Reports and charts
- PDF export
- Excel export
- Role-based administrator accounts
- Advanced search and filtering
- Audit logs
- Automated queue notifications
- Progressive Web App support
- API integration


31. PROJECT STATUS
==================

Status:

LIVE AND DEPLOYED

Customer Portal:

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php

Admin Portal:

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/admin/login.php

Hosting:

InfinityFree

Database:

MySQL


32. PROJECT SUMMARY
===================

OBR Queue Management System is a PHP and MySQL web application
that separates customer functionality from administrator
functionality.

The customer website provides the public interface.

The administrator website provides protected management
functionality.

PHP handles:

- Server-side processing
- Authentication
- Sessions
- Database communication
- CRUD operations

MySQL handles:

- Data storage
- Administrator records
- Customer information
- Feedback
- Queue-related records

HTML, CSS and JavaScript handle the user interface and
client-side interaction.

The complete application is deployed online and is accessible
through separate customer and admin portals.


33. DEVELOPER
=============

Developed by:

Saqib Anwar

Project:

OBR Queue Management System

Technologies:

PHP
MySQL
HTML5
CSS3
JavaScript


34. LIVE LINKS
==============

CUSTOMER PORTAL:

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/customer/index.php


ADMIN PORTAL:

https://obr-queue-management.infinityfreeapp.com/OBR_QMS/admin/login.php


======================== END OF README ========================
