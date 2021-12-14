<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_NAME, $DATABASE_PASS, $DATABASE_USER);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL' . mysqli_connect_error()); // This checks for errors when connecting to MySQL, if there are then the script stops
}

// This checks whether the data for the login was entered 
if (!isset($_POST['username'], $_POST['password'])) { 
    exit('Make sure you entered the correct username and passowrd!');
}

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result(); // This stores the results, allowing us to see if the accounts where created in the database

    // This part of code verifies the password
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();

        if (password_verify($_POST['password'], $password)) {
            session_regenerate_id();

            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;

            echo 'Welcome' .$_SESSION['name'] . '!';
        } else {
            echo 'Incorrect username and/or password!';
        }
    }    else {
        echo 'Incorrect username and/or password!';
    }

    $stmt->close();
}


// Since my experience with php is limited, this was coded using help from Google. Probably will lose marks but oh well. 