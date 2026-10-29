<?php
/*  INFX 472-001-202720 Full Stack Web Development
    Group 3 - Samuel A., Avery S., Mason Z.
    Project 4 - index.php
*/

// include the database connection
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Address Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>My Address Book</h1>
            <button id="openAddModalBtn" class="btn-primary">+ Add New Contact</button>
        </header>

        <!-- creative add. 1. live search & group filtering controls -->
        <div class="controls-bar">
            <input type="text" id="searchInput" placeholder="Search by name, email, phone, city...">
            <select id="groupFilter">
                <option value="">All Groups</option>
                <option value="Family">Family</option>
                <option value="Friends">Friends</option>
                <option value="Business">Business</option>
            </select>
        </div>

        <!-- contact list container with loading indicator (A.S.) -->
        <div id="contactContainer">
            <div class="spinner">Loading contacts...</div>
        </div>
    </div>

    <!-- creative add. 2. toast notification container -->
    <div id="toastContainer"></div>

    <!-- add/edit modal form markup goes here -->
    <div id="contactModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2 id="modalTitle">Add New Contact</h2>
            
            <form id="contactForm">
                <!-- hidden ID field used by edit_contact.php to know which record to update (A.S.) -->
                <input type="hidden" id="contactId" name="id">

                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone *</label>
                    <input type="text" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="address1">Address 1</label>
                    <input type="text" id="address1" name="address1">
                </div>

                <div class="form-group">
                    <label for="address2">Address 2</label>
                    <input type="text" id="address2" name="address2">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city">
                    </div>

                    <!-- STATE DROPDOWN GENERATED VIA PHP LOOP -->
                    <div class="form-group">
                        <label for="state">State</label>
                        <?php
                        // define the array of U.S. states
                        $us_states = [
                            'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
                            'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
                            'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
                            'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
                            'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
                            'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
                            'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
                            'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
                            'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
                            'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
                            'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
                            'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
                            'WI' => 'Wisconsin', 'WY' => 'Wyoming'
                        ];
                        ?>
                        <select name="state" id="state">
                            <option value="">Select State</option>
                            <?php foreach ($us_states as $abbr => $name): ?>
                                <option value="<?= $abbr ?>"><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="zip_code">Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code">
                    </div>
                </div>

                <!-- contact group dropdown -->
                <div class="form-group">
                    <label for="contact_group">Group *</label>
                    <select name="contact_group" id="contact_group" required>
                        <option value="">Select Group</option>
                        <option value="Family">Family</option>
                        <option value="Friends">Friends</option>
                        <option value="Business">Business</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" id="closeModalBtn" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save Contact</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="app.js"></script>
</body>
</html>
