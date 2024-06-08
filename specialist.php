<?php
include 'partials/header.php';

// Отримання ID спеціаліста з URL
$specialistId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Перевірка наявності ID
if ($specialistId > 0) {
    // Підготовка та виконання SQL-запиту для отримання інформації про спеціаліста
    $stmt = $connection->prepare(
        "SELECT 
            specialists.id AS specialist_id, 
            specialists.name, 
            specialists.age, 
            specialists.phone, 
            specialists.photo_path, 
            specialists.email, 
            specialists.description 
        FROM 
            specialists 
        WHERE 
            specialists.id = ?"
    );

    if (!$stmt) {
        die("SQL statement failed: " . $connection->error);
    }

    $stmt->bind_param('i', $specialistId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Перевірка наявності результату
    if ($result->num_rows > 0) {
        $specialist = $result->fetch_assoc();
        
        // Отримання спеціалізацій спеціаліста
        $specStmt = $connection->prepare(
            "SELECT 
                specialties.title AS specialization_title 
            FROM 
                specialist_specialties 
            JOIN 
                specialties ON specialties.id = specialist_specialties.specialty_id 
            WHERE 
                specialist_specialties.specialist_id = ?"
        );

        if (!$specStmt) {
            die("SQL statement failed: " . $connection->error);
        }

        $specStmt->bind_param('i', $specialistId);
        $specStmt->execute();
        $specResult = $specStmt->get_result();
        
        $specializations = [];
        while ($row = $specResult->fetch_assoc()) {
            $specializations[] = $row['specialization_title'];
        }

        $specStmt->close();
    } else {
        echo "No specialist found with the given ID.";
        exit();
    }

    $stmt->close();
    $connection->close();
} else {
    echo "Invalid specialist ID.";
    exit();
}
?>
<section class="opened-specialist">
    <div class="container opened-specialist_container">
        <div class="specialist__info">
            <div class="specialist__thumbnail">
                <img src="<?= $specialist['photo_path'] ?>" alt="<?= $specialist['name'] ?>">
            </div>
            <div class="specialist__name">
                <h3><?= $specialist['name'] ?></h3>
                <h5><?= $specialist['age'] ?> years old</h5>
            </div>
            <div class="specializations">
                <?php foreach ($specializations as $specialization): ?>
                    <div class="specialization"><h5><?= $specialization ?></h5></div>
                <?php endforeach; ?>
            </div>
            <div class="additional__info">
                <h5><?= $specialist['description'] ?></h5>
            </div>
            <div class="contact__info">
                <div class="contact__button" data-name="<?= $specialist['name'] ?>" data-phone="<?= $specialist['phone'] ?>" data-email="<?= $specialist['email'] ?>">Contact</div>
            </div>
        </div>
    </div>
</section>
<script src="js/main.js"></script> <!-- Підключає main.js -->
</body>
</html>