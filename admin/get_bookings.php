<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

if (current_admin() === null) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access - Please login first']);
    exit;
}

if (isset($_GET['export']) && (string) $_GET['export'] === '1') {
    $date = (string) ($_GET['date'] ?? date('Y-m-d'));
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        echo 'Invalid date format';
        exit;
    }

    $statement = app()->pdo()->prepare(
        'SELECT b.id AS "Booking ID", f.name AS "Facility", f.location AS "Location",
                u.display_name AS "Username", u.email AS "Email",
                b.booking_date AS "Date", b.start_time AS "Start Time", b.end_time AS "End Time",
                b.purpose AS "Purpose", b.status AS "Status",
                b.created_at AS "Created At", b.cancelled_at AS "Cancelled At"
         FROM bookings b
         LEFT JOIN users u ON u.id = b.user_id
         LEFT JOIN facilities f ON f.id = b.facility_id
         WHERE b.booking_date = :booking_date
         ORDER BY b.start_time, f.name'
    );
    $statement->execute([':booking_date' => $date]);
    $rows = $statement->fetchAll();

    $filename = 'bookings_' . $date . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    if (!empty($rows)) {
        fputcsv($output, array_keys($rows[0]), ',', '"', '\\', "\n");
        foreach ($rows as $row) {
            fputcsv($output, $row, ',', '"', '\\', "\n");
        }
    } else {
        fputcsv($output, ['Booking ID', 'Facility', 'Location', 'Username', 'Email', 'Date', 'Start Time', 'End Time', 'Purpose', 'Status', 'Created At', 'Cancelled At'], ',', '"', '\\', "\n");
        fputcsv($output, ['No bookings found for ' . $date], ',', '"', '\\', "\n");
    }

    fclose($output);
    exit;
}

header('Content-Type: application/json');

if (isset($_GET['booking_id'])) {
    $bookingId = (int) $_GET['booking_id'];
    $statement = app()->pdo()->prepare(
        'SELECT b.id AS booking_id, b.user_id, b.facility_id, b.booking_date, b.start_time, b.end_time,
                b.purpose, b.status, b.created_at, b.cancelled_at,
                u.display_name AS username, u.email,
                f.name AS facility_name, f.location, f.capacity, f.type AS facility_type
         FROM bookings b
         LEFT JOIN users u ON u.id = b.user_id
         LEFT JOIN facilities f ON f.id = b.facility_id
         WHERE b.id = :id'
    );
    $statement->execute([':id' => $bookingId]);
    $booking = $statement->fetch();

    if ($booking) {
        echo json_encode(['success' => true, 'booking' => $booking]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
    }
    exit;
}

$date = (string) ($_GET['date'] ?? date('Y-m-d'));
$status = (string) ($_GET['status'] ?? '');

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format']);
    exit;
}

$conditions = ['b.booking_date = :booking_date'];
$params = [':booking_date' => $date];
if ($status !== '') {
    $conditions[] = 'b.status = :status';
    $params[':status'] = $status;
}
$whereClause = 'WHERE ' . implode(' AND ', $conditions);

$statement = app()->pdo()->prepare(
    "SELECT b.id AS booking_id, b.user_id, b.facility_id, b.booking_date, b.start_time, b.end_time,
            b.purpose, b.status, b.created_at, b.cancelled_at,
            u.display_name AS username, u.email,
            f.name AS facility_name, f.location, f.capacity
     FROM bookings b
     LEFT JOIN users u ON u.id = b.user_id
     LEFT JOIN facilities f ON f.id = b.facility_id
     $whereClause
     ORDER BY b.start_time, f.name"
);
$statement->execute($params);
$bookings = $statement->fetchAll();

$total = count($bookings);
$confirmed = count(array_filter($bookings, static fn (array $b): bool => $b['status'] === 'confirmed'));
$cancelled = count(array_filter($bookings, static fn (array $b): bool => $b['status'] === 'cancelled'));

$facilityCount = (int) app()->pdo()->query('SELECT COUNT(*) FROM facilities WHERE is_active = 1')->fetchColumn();
$timeSlotsCount = 9;
$totalSlots = $facilityCount * $timeSlotsCount;
$utilizationRate = $totalSlots > 0 ? round(($confirmed / $totalSlots) * 100, 1) : 0.0;

echo json_encode([
    'success' => true,
    'bookings' => $bookings,
    'statistics' => [
        'total' => $total,
        'confirmed' => $confirmed,
        'cancelled' => $cancelled,
        'utilization_rate' => $utilizationRate,
    ],
    'date' => $date,
]);
