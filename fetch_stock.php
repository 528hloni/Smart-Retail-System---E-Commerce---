<?php
include('connection.php');

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT rim_id, quantity FROM rims");
    $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($stocks);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>