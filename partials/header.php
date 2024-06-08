<?php
require 'config/database.php';


$authorized = isset($_SESSION['user-id']) ?? null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=specializations.0">
    <title>Medical specialists selection system</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,specializations00..900;specializations,specializations00..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="container nav__container">
            <a href="<?= ROOT_URL ?>" class="nav__name">Medical specialists selection system</a>
            <ul class="nav__items">
                <li><a href="<?= ROOT_URL ?>support.php">Support</a></li>
                <?php if ($authorized): ?>
                    <li class="nav__profile">
                        <div id="profile"><a href="<?= ROOT_URL ?>profile.php">Profile</a></div>
                        <ul>
                            <li><a href="<?= ROOT_URL ?>history.php">History</a></li>
                            <li><a href="<?= ROOT_URL ?>logout.php">Log Out</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="<?= ROOT_URL ?>signin.php">Sign In</a></li>
                <?php endif; ?>
            </ul>

            <button id="open__nav-btn"><i class="uil uil-bars"></i></a></button>
            <button id="close__nav-btn"><i class="uil uil-multiply"></i></button>
        </div>
    </nav>

    <script src="<?= ROOT_URL ?>./js/main.js"></script>