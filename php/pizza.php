<?php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // READ – összes pizza lekérése
    case 'GET':
        $stmt = $dbh->query("SELECT * FROM pizza ORDER BY nev");
        $pizzak = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // vegetarianus mezőt boolean-ná alakítjuk
        foreach ($pizzak as &$p) {
            $p['vegetarianus'] = (bool)$p['vegetarianus'];
        }
        echo json_encode($pizzak);
        break;

    // CREATE – új pizza hozzáadása
    case 'POST':
        $adat = json_decode(file_get_contents('php://input'), true);
        $stmt = $dbh->prepare("INSERT INTO pizza (nev, kategorianev, vegetarianus) VALUES (?, ?, ?)");
        $stmt->execute([$adat['nev'], $adat['kategorianev'], $adat['vegetarianus'] ? 1 : 0]);
        echo json_encode(["uzenet" => "Pizza hozzáadva!", "nev" => $adat['nev']]);
        break;

    // UPDATE – pizza módosítása
    case 'PUT':
        $adat = json_decode(file_get_contents('php://input'), true);
        $stmt = $dbh->prepare("UPDATE pizza SET kategorianev=?, vegetarianus=? WHERE nev=?");
        $stmt->execute([$adat['kategorianev'], $adat['vegetarianus'] ? 1 : 0, $adat['nev']]);
        echo json_encode(["uzenet" => "Pizza frissítve!", "nev" => $adat['nev']]);
        break;

    // DELETE – pizza törlése
    case 'DELETE':
        $adat = json_decode(file_get_contents('php://input'), true);
        $stmt = $dbh->prepare("DELETE FROM pizza WHERE nev=?");
        $stmt->execute([$adat['nev']]);
        echo json_encode(["uzenet" => "Pizza törölve!", "nev" => $adat['nev']]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["hiba" => "Nem engedélyezett metódus"]);
        break;
}
?>