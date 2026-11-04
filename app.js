/*  INFX 472-001-202720 Full Stack Web Development
    Group 3 - Samuel A., Avery S., Mason Z.
    Project 4 - app.js
*/

$(document).ready(function() {
    let contactsRequest = null;
    let saving = false;

    //Show a temporary notification.
    window.showToast = function(message, type = 'success') {
        const toast = $('<div>')
            .addClass('toast')
            .addClass(type)
            .text(message);

        $('#toastContainer').append(toast);

        setTimeout(function() {
            toast.fadeOut(400, function() {
                $(this).remove();
            });
        }, 3000);
    };

    //Load contacts without refreshing the page.
    function loadContacts() {
        //Stop an older search from replacing newer results.
        if (contactsRequest && contactsRequest.readyState !== 4) {
            contactsRequest.abort();
        }

        $('#contactContainer').html(
            '<div class="spinner">Loading contacts...</div>'
        );

        contactsRequest = $.ajax({
            url: 'contacts.php',
            method: 'GET',
            data: {
                search: $('#searchInput').val(),
                group: $('#groupFilter').val()
            },
            success: function(response) {
                $('#contactContainer').html(response);
            },
            error: function(xhr, status) {
                if (status === 'abort') {
                    return;
                }

                $('#contactContainer').text('Unable to load contacts.');
                showToast('Failed to load contacts.', 'error');
            }
        });
    }

    //Live search and group filtering.
    $('#searchInput, #groupFilter').on('input change', function() {
        loadContacts();
    });

    //pen an empty form to add a contact.
    $('#openAddModalBtn').on('click', function(e) {
        e.preventDefault();

        if (saving) {
            return;
        }

        $('#contactForm')[0].reset();
        $('#contactId').val('');
        $('#modalTitle').text('Add New Contact');
        $('#contactModal').show();
    });

    //Close the form without saving.
    $('#closeModalBtn').on('click', function(e) {
        e.preventDefault();

        if (!saving) {
            $('#contactModal').hide();
        }
    });

    //Fill the form using the Edit button's contact data.
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();

        if (saving) {
            return;
        }

        const contact = $(this).data('contact');

        if (!contact || typeof contact !== 'object' || !contact.id) {
            showToast(
                'This Edit button is missing its contact data.',
                'error'
            );
            return;
        }

        $('#contactForm')[0].reset();
        $('#contactId').val(contact.id);

        const fields = [
            'first_name', 'last_name', 'email', 'phone',
            'address1', 'address2', 'city', 'state',
            'zip_code', 'contact_group', 'notes'
        ];

        fields.forEach(function(field) {
            $('#' + field).val(contact[field] ?? '');
        });

        $('#modalTitle').text('Edit Contact');
        $('#contactModal').show();
    });

    //Submit the form to Add or Edit using POST.
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();

        if (saving || !this.reportValidity()) {
            return;
        }

        const form = $(this);
        const url = $('#contactId').val()
            ? 'edit_contact.php'
            : 'add_contact.php';

        saving = true;

        const buttons = form.find('button');
        buttons.prop('disabled', true);

        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response && response.status === 'success') {
                    showToast(response.message, 'success');
                    $('#contactModal').hide();
                    form[0].reset();
                    $('#contactId').val('');
                    loadContacts();
                } else {
                    showToast(
                        response?.message || 'Unable to save the contact.',
                        'error'
                    );
                }
            },
            error: function() {
                showToast(
                    'Unable to save the contact. Please try again.',
                    'error'
                );
            },
            complete: function() {
                saving = false;
                buttons.prop('disabled', false);
            }
        });
    });

    //Ask for confirmation before deleting a contact.
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();

        const button = $(this);

        if (button.prop('disabled')) {
            return;
        }

        if (!confirm('Are you sure you want to delete this contact?')) {
            return;
        }

        button.prop('disabled', true);

        $.ajax({
            url: 'delete_contact.php',
            method: 'POST',
            data: {
                id: button.data('id')
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.status === 'success') {
                    showToast(response.message, 'success');
                    loadContacts();
                } else {
                    showToast(
                        response?.message || 'Unable to delete the contact.',
                        'error'
                    );
                }
            },
            error: function() {
                showToast(
                    'Unable to delete the contact. Please try again.',
                    'error'
                );
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });

    //Copy an email address or phone number.
    $(document).on('click', '.copyable', function(e) {
        e.preventDefault();

        const textToCopy = $(this).text().trim();

        if (!navigator.clipboard) {
            showToast('Clipboard access is unavailable.', 'error');
            return;
        }

        navigator.clipboard.writeText(textToCopy).then(function() {
            showToast('Copied to clipboard: ' + textToCopy, 'success');
        }).catch(function() {
            showToast('Failed to copy text.', 'error');
        });
    });

    //Load the contact list when the page opens.
    loadContacts();
});
