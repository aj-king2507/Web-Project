# Web-Project
The project involves all backend tasks related to the  User Authentication system
It consists of:
1.User registration with username , password and email.
2.Password hashing using password_hash()
3.The login system with session handling
4.Login users are accessible to dashboard
5.Login users are given the option to logout on the dashboard
6.Error messages are displayed for invalid inputs and duplicate accounts.
---------
To import the User_authentication database:
1.Go to XAMPP , start Apache and MySql.
2.Go to http://localhost/phpmyadmin
3.Create a new databse by clicking new and name it user_authentication
4.Create a table users :
    id INT PRIMARY KEY AUTO_INCREMENT
    username VARCHAR(50)
    email VARCHAR(50)
    password VARCHAR(255)
5.Place the UserAuthentication folder in the htdocs of XAMPP
6.Update the db_connect.php with the following :
<?php
$servername="localhost";
$username="root";
$password="";
$dbname="user_authentication";
$conn=new mysqli($servername,$username,$password,$dbname);
if ($conn-> connect_error){
    die("database connection failed: ".$conn->connect_error);
}
?>
7.Open your browser and go to :http://localhost/UserAuthentication/Registration.php
------
After these steps you can register a new user through the Registration.php
you can login using the Login.php
