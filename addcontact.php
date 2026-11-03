<?php
/*  INFX 472-001-202720 Full Stack Web Development
    Group 3 - Samuel A., Avery S., Mason Z.
    Project 4 - addcontact.php
*/
header('Content-Type: application/json; charset=utf-8');

//Return the status and message expected by app.js.
function respond($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

//Changes must be submitted using POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    respond('error', 'Please submit this request using POST.');
}

//Collect the fields from the existing contact form.
$fields = [
    'first_name', 'last_name', 'email', 'phone',
    'address1', 'address2', 'city', 'state',
    'zip_code', 'contact_group', 'notes'
];
$contact = [];

foreach ($fields as $field) {
    $value = $_POST[$field] ?? '';
    if (!is_string($value)) {
        respond('error', 'Invalid form data.');
    }
    $contact[$field] = trim($value);
}

//Validate on the server as well as in the browser.
$required = ['first_name', 'last_name', 'email', 'phone', 'contact_group'];
foreach ($required as $field) {
    if ($contact[$field] === '') {
        respond('error', 'Please complete all required fields.');
    }
}

if (!filter_var($contact['email'], FILTER_VALIDATE_EMAIL)) {
    respond('error', 'Please enter a valid email address.');
}

$groups = ['Family', 'Friends', 'Business'];
if (!in_array($contact['contact_group'], $groups, true)) {
    respond('error', 'Please select a valid contact group.');
}

//State is optional, but must match a dropdown option
$states = explode(' ', 'AL AK AZ AR CA CO CT DE FL GA HI ID IL IN IA KS KY LA ME MD MA MI MN MS MO MT NE NV NH NJ NM NY NC ND OH OK OR PA RI SC SD TN TX UT VT VA WA WV WI WY');
if ($contact['state'] !== '' && !in_array($contact['state'], $states, true)) {
    respond('error', 'Please select a valid state.');
}

require_once __DIR__ . '/db.php';

try {
    //ID and date added use defaults.
    $stmt = $pdo->prepare(
        "INSERT INTO contacts
            (first_name, last_name, email, phone, address1, address2,
             city, state, zip_code, contact_group, notes)
         VALUES
            (:first_name, :last_name, :email, :phone, :address1, :address2,
             :city, :state, :zip_code, :contact_group, :notes)"
    );
    $stmt->execute($contact);
    respond('success', 'Contact added successfully.');
} catch (PDOException $e) {
    respond('error', 'Unable to add the contact.');
}


?>
