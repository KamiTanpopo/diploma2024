<?php
require '../config/database.php';

// Перевірка параметра specialization_id
if (isset($_GET['specialization_id'])) {
    $specializationId = intval($_GET['specialization_id']);

    // Переконайтеся, що підключення відбулося успішно
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Підготуйте та виконайте SQL-запит для отримання спеціалістів
    $stmt = $connection->prepare(
        "SELECT 
            specialties.id AS specialty_id, 
            specialties.title, 
            specialists.id AS specialist_id, 
            specialists.name, 
            specialists.age, 
            specialists.phone, 
            specialists.photo_path,
            specialists.email
        FROM 
            specialists 
        JOIN 
            specialist_specialties ON specialists.id = specialist_specialties.specialist_id 
        JOIN 
            specialties ON specialties.id = specialist_specialties.specialty_id 
        WHERE 
            specialties.id = ? 
        GROUP BY 
            specialists.id"
    );

    // Перевірте, чи правильний SQL-запит
    if (!$stmt) {
        die("SQL statement failed: " . $connection->error);
    }

    // Зв'яжіть параметр з SQL-запитом
    $stmt->bind_param('i', $specializationId); // Зв'яжіть параметр з типом даних 'i' (ціле число)
    $stmt->execute();
    $result = $stmt->get_result();
    $specialists = [];

    // Отримання результатів
    while ($row = $result->fetch_assoc()) {
        // При збереженні результату зразу додамо масив для спеціалізацій
        $row['specializations'] = [];
        $specialists[$row['specialist_id']] = $row;
    }

    // Отримуємо спеціалізації для кожного спеціаліста
    if (!empty($specialists)) {
        $specialistIds = implode(',', array_keys($specialists));

        $specStmt = $connection->prepare(
            "SELECT 
                specialist_specialties.specialist_id, 
                specialties.title AS specialization_title
            FROM 
                specialist_specialties
            JOIN 
                specialties ON specialties.id = specialist_specialties.specialty_id 
            WHERE 
                specialist_specialties.specialist_id IN ($specialistIds)"
        );

        if (!$specStmt) {
            die("SQL statement failed: " . $connection->error);
        }

        $specStmt->execute();
        $specResult = $specStmt->get_result();

        while ($specRow = $specResult->fetch_assoc()) {
            $specialists[$specRow['specialist_id']]['specializations'][] = $specRow['specialization_title'];
        }

        $specStmt->close();
    }

    // Перетворюємо спеціалістів у простий масив для JSON-відповіді
    $specialists = array_values($specialists);

    // Повертаємо результати у форматі JSON
    echo json_encode(['specialists' => $specialists]);

    // Закриваємо statement та з'єднання
    $stmt->close();
    $connection->close();
} else {
    echo json_encode(['specialists' => []]);
}