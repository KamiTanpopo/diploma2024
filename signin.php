<?php
include 'partials/header.php';
session_start();

$email = $_SESSION['signin-data']['email'] ?? null;
$password = $_SESSION['signin-data']['password'] ?? null;

unset($_SESSION['signin-data']);
?>
    <section class="sign-in">
        <div class="container sign-in__container">
            <h3>Sign In</h3>
            <?php if(isset($_SESSION['signin'])) : ?>
                <div class="alert__message error">
                    <p>
                        <?= $_SESSION['signin'];
                        unset($_SESSION['signin']);?>
                    </p>
                </div>
                
            <?php endif?>
            <form action="<?= ROOT_URL ?>logic/signin_logic.php" method="POST">
                <input type="email" name="email" value="<?= $email?>" placeholder="Email">
                <input type="password" name="password" value="<?= $password?>" placeholder="Password">
                <button type="submit" name="submit" class="btn">Sign In</button>
            </form>
        </div>
    </section>

</body>
</html>
