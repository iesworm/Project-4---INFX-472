<?php
/*  INFX 472-001-202720 Full Stack Web Development
    Group 3 - Samuel A., Avery S., Mason Z.
    Project 4 - contacts.php - retrieves contacts from the database & supports optional search queries and group filters for the live search creative feature
*/

// include the database connection
require_once 'db.php';

// capture optional filter parameters sent via GET request (A.S.)
$search = $_GET['search'] ?? '';
$group = $_GET['group'] ?? '';

$sql = "SELECT * FROM contacts WHERE 1=1";
$params = [];

// apply live search filtering across name, email, phone, or city (A.S.)
if (!empty($search)) {
    $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ? OR city LIKE ?)";
    $searchTerm = "%$search%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
}

// apply group filter if selected (A.S.)
if (!empty($group)) {
    $sql .= " AND contact_group = ?";
    $params[] = $group;
}

$sql .= " ORDER BY date_added DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$contacts = $stmt->fetchAll();

// handle empty results (A.S.)
if (empty($contacts)) {
    echo '<div class="no-contacts">No contacts found matching your criteria.</div>';
    exit;
}

// output formatted HTML cards for each contact (A.S.)
foreach ($contacts as $c) {
    $badgeClass = strtolower($c['contact_group']);
    echo '
    <div class="contact-card" data-id="' . $c['id'] . '">
        <div class="card-header">
            <h3>' . htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) . '</h3>
            <span class="badge ' . $badgeClass . '">' . htmlspecialchars($c['contact_group']) . '</span>
        </div>
        <div class="card-body">
            <p><strong>Phone:</strong> <span class="copyable" title="Click to copy">' . htmlspecialchars($c['phone']) . '</span></p>
            <p><strong>Email:</strong> <span class="copyable" title="Click to copy">' . htmlspecialchars($c['email']) . '</span></p>
            <p><strong>Address:</strong> ' . htmlspecialchars($c['address1'] . ($c['address2'] ? ', ' . $c['address2'] : '') . ', ' . $c['city'] . ', ' . $c['state'] . ' ' . $c['zip_code']) . '</p>
            ' . (!empty($c['notes']) ? '<p><strong>Notes:</strong> ' . htmlspecialchars($c['notes']) . '</p>' : '') . '
        </div>
        <div class="card-actions">
            <button class="btn-edit" data-id="' . $c['id'] . '">Edit</button>
            <button class="btn-delete" data-id="' . $c['id'] . '">Delete</button>
        </div>
    </div>';
}
?>
