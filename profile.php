<?php
include 'partials/header.php';

$id = filter_var($_SESSION['user-id'], FILTER_SANITIZE_NUMBER_INT);
$query1 = "SELECT name FROM users WHERE id=$id";
$result1 = mysqli_query($connection, $query1);
$user_name = mysqli_fetch_assoc($result1);
$query2 = "SELECT email FROM users WHERE id=$id";
$result2 = mysqli_query($connection, $query2);
$user_email = mysqli_fetch_assoc($result2);

?>



    <section class="description">
        <div class="container description_container">
            <div class="description_block">
                <h3>Profile</h3>
                <br>
                <h4 style = "font-size: 1.2rem; margin-bottom:0.4rem; 
                            color:rgb(24, 40, 96); font-weight:800">
                    <?= $user_name['name'] ?>
                </h4>
                <h4><?= $user_email['email'] ?></h4>
            </div>
        </div>
    </section>
    

</body>
</html>