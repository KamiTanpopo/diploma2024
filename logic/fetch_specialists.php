<?php
require '../config/database.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$specializationIds = $data['specializations'] ?? [];

if (!empty($specializationIds)) {
    $placeholders = implode(',', array_fill(0, count($specializationIds), '?'));
    $types = str_repeat('i', count($specializationIds));

    // Перший запит для отримання спеціалістів за обраними спеціалізаціями
    $sql = " 
        SELECT specialists.id AS specialist_id, specialists.name, specialists.age, specialists.phone, specialists.photo_path, specialists.email
        FROM specialists
        JOIN specialist_specialties ON specialists.id = specialist_specialties.specialist_id
        JOIN specialties ON specialties.id = specialist_specialties.specialty_id
        WHERE specialties.id IN ($placeholders)
        GROUP BY specialists.id
        ORDER BY specialists.id 
    ";

    $stmt = $connection->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($types, ...$specializationIds);
        $stmt->execute();
        $result = $stmt->get_result();
        $specialists = [];

        while ($row = $result->fetch_assoc()) {
            $specialists[$row['specialist_id']] = [
                'info' => [
                    'id' => $row['specialist_id'],
                    'name' => $row['name'],
                    'age' => $row['age'],
                    'phone' => $row['phone'],
                    'photo_path' => $row['photo_path'],
                    'email' => $row['email']
                ],
                'specializations' => []
            ];
        }
        $stmt->close();

        // Другий запит для отримання всіх спеціалізацій спеціалістів
        if (!empty($specialists)) {
            $specialistIds = array_keys($specialists);
            $placeholders = implode(',', array_fill(0, count($specialistIds), '?'));
            $types = str_repeat('i', count($specialistIds));

            $sql = "
                SELECT specialists.id AS specialist_id, specialties.id AS specialization_id, specialties.title AS specialization
                FROM specialists
                JOIN specialist_specialties ON specialists.id = specialist_specialties.specialist_id
                JOIN specialties ON specialties.id = specialist_specialties.specialty_id
                WHERE specialists.id IN ($placeholders)
                ORDER BY specialists.id
            ";

            $stmt = $connection->prepare($sql);
            if ($stmt) {
                $stmt->bind_param($types, ...$specialistIds);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $specialists[$row['specialist_id']]['specializations'][] = [
                        'id' => $row['specialization_id'],
                        'name' => $row['specialization']
                    ];
                }
                $stmt->close();
            }
        }

        echo json_encode(array_values($specialists));
    } else {
        echo json_encode(['error' => 'Failed to prepare statement']);
    }
} else {
    echo json_encode(['specialists' => []]);
}

$connection->close();
