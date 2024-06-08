<?php
require '../config/database.php';
session_start();



if(isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!$email){
        $_SESSION['signin'] = "Email required";
    } elseif (!$password){
        $_SESSION['signin'] = "Password required";
    } else {
        $fetch_user_query = "SELECT * FROM users WHERE email='$email'";
        $fetch_user_result = mysqli_query($connection, $fetch_user_query);

        if(mysqli_num_rows($fetch_user_result) == 1){
            $user_record = mysqli_fetch_assoc($fetch_user_result);
            $db_password = $user_record['password_hash'];
            $hashed_password = hash('sha1', $password);
            if($db_password == $hashed_password){
                $_SESSION['user-id'] = $user_record['id'];
                header('location:' . ROOT_URL . 'index.php');
            } else {
                $_SESSION['signin'] = "Incorrect data";
            }
        } else {
            $_SESSION['signin'] = "No such account exists";
        }
    }

    if (isset($_SESSION['signin'])){
        $_SESSION['signin-data'] = $_POST;
        header('location:' . ROOT_URL . 'signin.php');
        die();
    }

} else {
    header('location: '. ROOT_URL . 'signin.php');
    die();
}
