<?php
    $dsn = 'mysql:host=localhost;dbname=photo_db';
    $username = 'photo';
    $password = 'ndokuda';

    try {
        $db = new PDO($dsn, $username, $password);
        unset($username);
        unset($password);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('errors/database_error.php');
        exit();
    }
?>
