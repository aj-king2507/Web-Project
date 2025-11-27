[readme.md](https://github.com/user-attachments/files/23798384/readme.md)# Web-Project

[Upload# Web Project: User Authentication System

---

The project involves all backend tasks related to the **User Authentication system**.

It consists of:

* **User registration** with username, password, and email
* **Password hashing** using `password_hash()`
* **Login system** with session handling
* Logged-in users are accessible to the **dashboard**
* Logged-in users can **logout** from the dashboard
* **Error messages** are displayed for invalid inputs and duplicate accounts

---

## Database Setup

To import the `user_authentication` database:

1. Open XAMPP and start Apache and MySQL
2. Go to [phpMyAdmin](http://localhost/phpmyadmin)
3. Create a new database named `user_authentication`
4. Create a **table `users`** with the following columns:

```sql
id       INT PRIMARY KEY AUTO_INCREMENT
username VARCHAR(50)
email    VARCHAR(50)
password VARCHAR(255)
```

---


1. Place the **UserAuthentication** folder inside `htdocs` of XAMPP
2. Update `db_connect.php` with the following code:

```php
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_authentication";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
```

3. Open your browser and go to:

```
http://localhost/UserAuthentication/Registration.php
```

4. You can now **register a new user** through `Registration.php`
5. You can **login** using `Login.php`

---


