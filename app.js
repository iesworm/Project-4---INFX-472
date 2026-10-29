/*  INFX 472-001-202720 Full Stack Web Development
    Group 3 - Samuel A., Avery S., Mason Z.
    Project 4 - app.js
*/
    // load contacts initially on page load
    loadContacts();

    // creative add. 1. live search & group filtering listeners
    $('#searchInput, #groupFilter').on('input change', function() {
        loadContacts();
    });

    function loadContacts() {
        const searchQuery = $('#searchInput').val();
        const groupQuery = $('#groupFilter').val();

        // show loading indicator (A.S.)
        $('#contactContainer').html('<div class="spinner">Loading contacts...</div>');

        // fetch contacts via AJAX GET request (A.S.)
        $.ajax({
            url: 'contacts.php',
            method: 'GET',
            data: { search: searchQuery, group: groupQuery },
            success: function(response) {
                $('#contactContainer').html(response);
            },
            error: function() {
                showToast('Failed to load contacts.', 'error');
            }
        });
    }

    // creative add. 2. toast notification function
    window.showToast = function(message, type = 'success') {
        const toast = $('<div class="toast ' + type + '">' + message + '</div>');
        $('#toastContainer').append(toast);
        setTimeout(() => {
            toast.fadeOut(400, function() { $(this).remove(); });
        }, 3000);
    };

    // creative add. 3. click-to-copy functionality
    $(document).on('click', '.copyable', function() {
        const textToCopy = $(this).text();
        navigator.clipboard.writeText(textToCopy).then(() => {
            showToast('Copied to clipboard: ' + textToCopy, 'success');
        }).catch(err => {
            showToast('Failed to copy text.', 'error');
        });
    });

    // form submission handling (AJAX POST for add/edit) (A.S.)
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        const url = $('#contactId').val() ? 'edit_contact.php' : 'add_contact.php';
        
        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showToast(response.message, 'success');
                    $('#contactModal').hide();
                    $('#contactForm')[0].reset();
                    $('#contactId').val('');
                    loadContacts(); // refresh list without reloading page (A.S.)
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function() {
                showToast('An error occurred. Please try again.', 'error');
            }
        });
    });

    // delete contact confirmation & execution (A.S.)
    $(document).on('click', '.btn-delete', function() {
        if (confirm('Are you sure you want to delete this contact?')) {
            const id = $(this).data('id');
            $.ajax({
                url: 'delete_contact.php',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showToast(response.message, 'success');
                        loadContacts();
                    } else {
                        showToast(response.message, 'error');
                    }
                }
            });
        }
    });
});
