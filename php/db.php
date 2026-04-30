<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

//Lekérjük magát az adatbázis(Kérlek működj)

try {
    $dbh = new PDO(
        'mysql:host=mysql.omega;port=3306;dbname=adat1;charset=utf8',
        'adat1',
        'NakamuraHaru22',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch (PDOException $e) {
    echo json_encode(["hiba" => $e->getMessage()]);
    exit();
}
?>