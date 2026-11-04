<?php
/*  INFX 472-001-202720 Full Stack Web Development
    Group 3 - Samuel A., Avery S., Mason Z.
    Project 4 - deletecontact.php
*/
header('Content-Type: application/json; charset=utf-8');

//Return the status and message
function respond($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

//Changes must be submitted using POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    respond('error', 'Please submit this request using POST.');
}

//Validate the ID before accessing the database
$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false || $id < 1) {
    respond('error', 'Invalid contact ID.');
}

require_once __DIR__ . '/db.php';

try {
    //app.js asks for confirmation before sending this request
    $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        respond('error', 'Contact not found.');
    }

    respond('success', 'Contact deleted successfully.');
} catch (PDOException $e) {
    respond('error', 'Unable to delete the contact.');
}



?>
