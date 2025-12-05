# Web Project - README

## Table of Contents

1. **Introduction**
2. **System Requirements**
3. **Project Setup**
4. **Database Configuration**
5. **Web Server Configuration**
6. **Project Structure Overview**
7. **User Authentication**
8. **Database Interaction and Management**
9. **User Management**
10. **Appointment Management**
11. **Email Configuration and Communication**
12. **Styling and Design**
13. **Frontend and User Interaction**
14. **Admin Dashboard**
15. **Security Measures**
16. **Error Handling and Debugging**
17. **Testing and Quality Assurance**
18. **Deployment Instructions**
19. **Troubleshooting Common Issues**
20. **Future Improvements and Roadmap**
21. **Appendices**

---

## 1. Introduction

### Overview of the Project

This project is a web-based application designed to allow users to book appointments online and for administrators to manage these appointments and user data. The system allows easy user registration, login, appointment booking, and management. Admins can view, update, and delete appointments as well as manage users.

### Purpose and Goal of the Project

The purpose of this project is to provide an intuitive, secure, and user-friendly appointment booking system with admin functionalities. The goal is to provide an easy-to-use interface for both users and administrators while maintaining high standards of security and performance.

### Technologies and Tools Used

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Web Server**: Apache (via XAMPP)
- **Additional**: Composer for dependency management

---

## 2. System Requirements

### Hardware Requirements

- **Minimum**: 2GB RAM, 1GHz CPU, 10GB free disk space
- **Recommended**: 4GB RAM, 2GHz CPU, 20GB free disk space

### Software Requirements

- **XAMPP** (with Apache, MySQL, and PHP)
- **PHP** 7.4+ (XAMPP comes with PHP pre-installed)
- **MySQL** 5.7+ (included in XAMPP)
- **Composer** (for managing dependencies if required)

---

## 3. Project Setup

### Step 1: Install XAMPP

1. Download the XAMPP installer from [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html) based on your operating system.
2. Follow the installation instructions on the website.

### Step 2: Start Apache and MySQL

1. Open the XAMPP Control Panel.
2. Start both the **Apache** and **MySQL** services by clicking on the "Start" button next to each.

### Step 3: Import Database

1. Open your browser and navigate to `http://localhost/phpmyadmin/`.
2. Create a new database named `opalglow`.
3. Import the `opalglow.sql` file located in the root directory of the project.
   - Click on the "Import" tab.
   - Select the `opalglow.sql` file and click "Go" to import the database.

### Step 4: Extract Project Files

1. Extract the `Web-Project.zip` file to the **htdocs** directory inside your XAMPP installation folder.
   - Typically, this will be `C:\xampp\htdocs\Web-Project\`.

### Step 5: Configure Database Connection

1. Open the `db_connect.php` file.
2. Make sure the database connection details (host, username, password, database name) match your local XAMPP setup.
   ```php
   define('DB_HOST', '127.0.0.1');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Default XAMPP password is empty
   define('DB_NAME', 'opalglow');
---

## 4. Database Configuration

### Database Setup

The project uses the `opalglow.sql` file to create the necessary database tables. After importing the SQL file, ensure the following tables are present in the `opalglow` database:

* `appointments`
* `users`
* `services`

These tables manage user data, appointments, and service information respectively.

### Database Structure

1. **Users Table**: Stores user data such as usernames, passwords, and roles (admin or regular user).
2. **Appointments Table**: Stores booking details such as the user ID, service, and scheduled time.
3. **Services Table**: Lists available services that users can book.

---

## 5. Web Server Configuration

### Configuring Apache and MySQL

1. Ensure Apache and MySQL are running in the XAMPP Control Panel.
2. If the default ports for Apache (80) or MySQL (3306) are in use, change the ports in the XAMPP Control Panel.

   * To change the Apache port, click on "Config" -> "Apache (httpd.conf)" and change `Listen 80` to another available port (e.g., `Listen 8080`).
   * Similarly, change the MySQL port by clicking on "Config" -> "my.ini" and changing `port=3306`.

---

## 6. Project Structure Overview

### File Structure

* **`admin_*.php`**: Admin pages for managing users, appointments, settings.
* **`assets/`**: Contains static files like images, CSS, and JavaScript.
* **`functions.php`**: Helper functions used throughout the site.
* **`db_connect.php`**: Database connection file.
* **`index.php`**: The homepage of the site.
* **`register.php`**: Registration page for new users.
* **`login.php`**: Login page for users and admins.
* **`mail_config.php`**: Configuration for sending emails.

---

## 7. User Authentication

### Registration System

Users can register by visiting the `register.php` page, where they provide their details (username, password, email). The password is hashed using PHP’s `password_hash()` function to enhance security.

### Login System

Users can log in through the `login.php` page. The system verifies credentials by comparing the hashed password stored in the database with the user input.

### Admin Login

Admins can access the admin panel through `admin_login.php`, where they enter their credentials. Admin credentials are checked against the `users` table, and the admin session is started if the credentials match.

---

## 8. Database Interaction and Management

### Inserting, Updating, and Retrieving Data

1. **Insert Data**:

   * New users are inserted into the `users` table upon registration.
   * New appointments are created in the `appointments` table via the booking form on `booking.php`.
2. **Fetch Data**:

   * Appointments are fetched from the `appointments` table to display on both user and admin dashboards.
3. **Updating Data**:

   * Admins can edit user data (e.g., role, status) and appointment data (e.g., timeslot, status) via the admin interface.

---

## 9. User Management

### Admin Features

Admins can view and manage users using the `admin_users.php` page. This includes functionalities for:

* Creating new users.
* Editing user information (e.g., role, active status).
* Deleting users.

---

## 10. Appointment Management

### User Appointment Booking

Users can book appointments through the `booking.php` page. The page allows them to select services and preferred timeslots. The appointment data is then inserted into the `appointments` table.

### Admin Appointment Management

Admins can manage appointments through the `admin_appointments.php` page, which provides an overview of all appointments and options for editing or deleting them.

---

## 11. Email Configuration and Communication

### Email System

Emails are configured in the `mail_config.php` file. The project uses PHP’s `mail()` function for sending emails such as:

* Registration confirmation emails.
* Appointment reminders and confirmations.

---

## 12. Styling and Design

### Customization

All styles are contained in the `assets/css` folder. You can modify the design by editing the CSS files to change the look and feel of the website. Adjust colors, fonts, and layout to suit your needs.

---

## 13. Frontend and User Interaction

### User Experience

The frontend is designed to be user-friendly, with clear forms for registration, login, and booking. JavaScript is used for form validation (e.g., required fields, date format checks).

---

## 14. Admin Dashboard

### Features of the Admin Dashboard

The admin dashboard (`admin_dashboard.php`) includes:

* Overview of total users and appointments.
* Graphical statistics (if implemented).
* Quick links to user management and appointment management.

---

## 15. Security Measures

### Password Security

Passwords are stored securely using PHP’s `password_hash()` function, and verification is done using `password_verify()` during login.

### Protection Against SQL Injection

Prepared statements are used in all database interactions to prevent SQL injection.

---

## 16. Error Handling and Debugging

### Error Handling

PHP errors are displayed when debugging is enabled in the `php.ini` file. For production, errors should be logged and not displayed on the page.

---

## 17. Testing and Quality Assurance

### Testing Procedures

Test all major functionalities (registration, login, booking, appointment management) to ensure proper operation. Use tools like PHPUnit for unit testing and manual testing for front-end elements.

---

## 18. Deployment Instructions

### Moving to a Live Server

1. **Upload Files**: Upload the entire project directory to your web host's root directory (e.g., `public_html`).
2. **Update Configuration**: Update `db_connect.php` with the live database credentials.
3. **Test**: Thoroughly test all functionality on the live server.

---

## 19. Troubleshooting Common Issues

### Database Issues

* **Error**: `Can't connect to database`

  * Solution: Check the credentials in `db_connect.php` and ensure MySQL is running.

### Permission Issues

* **Error**: File permissions for `uploads/` directory are incorrect.

  * Solution: Set the correct write permissions for the directory.

---

## 20. Future Improvements and Roadmap

### Planned Features

* Integrate payment gateway for appointment bookings.
* Multi-language support.
* Admin role-based permissions for better control.

---

## 21. Appendices

### Code Snippets

**Sample SQL Query** for fetching appointments:

```sql
SELECT * FROM appointments WHERE user_id = 1;
```

### License Information

This project is licensed under the MIT License.

---

```
