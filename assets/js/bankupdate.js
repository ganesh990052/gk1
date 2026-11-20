// Handle "Add" button click
$('#openAddBankDetailModal').on('click', function() {
    // Clear previous data
    $('#addBankDetailForm')[0].reset();

    // Open the Bootstrap Modal
    $('#addBankDetailModal').modal('show');

    // Fetch user information via AJAX and populate the select dropdown
    $.ajax({
        url: '/chite-fund/get-all-users',
        method: 'GET',
        success: function(response) {
            // Populate the select dropdown with user options
            $('#user_id').html('<option value="" disabled selected>Select User</option>');
            $.each(response.users, function(index, user) {
                $('#user_id').append('<option value="' + user.id + '">' + user.name + '</option>');
            });
        },
        error: function(error) {
            console.error('Error fetching user information:', error);
        }
    });
});

// Handle form submission within the Bootstrap Modal
$('#addBankDetailForm').submit(function(e) {
    e.preventDefault();

    // Check if required fields are filled
    if (!$('#user_id').val()) {
        // Show an error message or handle it as needed
        console.error('Please select a user.');
        return;
    }

    // Make AJAX request to add a new bank detail
    $.ajax({
        url: '/chite-fund/add-bank-detail',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            // Handle success
            console.log(response.message);
    
            // Close the Bootstrap Modal
    
            // Show success notification using SweetAlert
            Swal.fire({
                title: 'Success!',
                text: 'Bank details Insert successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 1000);
            $('#addBankDetailModal').modal('hide');

            // Optionally, you can update the UI or perform other actions
        },
        error: function(error) {
            // Handle error
            console.error('Error adding bank detail:', error);
        }
    });
    
});


$(document).ready(function() {
    var currentBankId;

    // DataTable initialization code
    $('#DataTable').DataTable();

    // Read button click event
    $('.bank-details-btn-edit').on('click', function() {
        currentBankId = $(this).data('id');
        // Fetch existing data using AJAX and display it in the modal
        $.ajax({
            method: 'GET',
            url: '/chite-fund/get-bank-detail/' + currentBankId,
            success: function(response) {
                // Display existing data in the Read modal
                $('#id').val(response.id);
                $('#editBankDetailModal').modal('show');
                $('#account_number').val(response.account_number);
                $('#editedBankName').val(response.bank_name);
                $('#account_holder_name').val(response.account_holder_name);
                $('#branch_name').val(response.branch_name);
                $('#ifsc_code').val(response.ifsc_code);
                $('#type_of_account').val(response.type_of_account);

                
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    // Edit button click event
    // $('.btn-edit').on('click', function() {
    //     currentBankId = $(this).data('id');
    //     $('#editBankDetailModal').modal('show');
    // });

    // When Save button in modal is clicked
    $('#btnSaveEdit').on('click', function() {
        var currentBankId = $('#id').val();

    $.ajax({
        url: '/chite-fund/update-bank-detail/' + currentBankId,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            account_number: $('#account_number').val(),
            bank_name: $('#editedBankName').val(),
            account_holder_name: $('#account_holder_name').val(),
            branch_name: $('#branch_name').val(),
            ifsc_code: $('#ifsc_code').val(),
            type_of_account: $('#type_of_account').val(),
            // Add any other fields you want to update
        },
        success: function(response) {
            // Handle success
            console.log(response.message);

            // Show success notification with Swal
            Swal.fire({
                title: 'Success!',
                text: 'Bank details updated successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 1000);
        },
        error: function(error) {
            // Handle error
            console.error('Error updating bank details:', error);

            // Show error notification with Swal
            Swal.fire({
                title: 'Error!',
                text: 'Failed to update bank details. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
           
        }
        });
    });
});

// Assuming you have a button or event that triggers the deletion
$('.bank-details-btn-link').on('click', function() {
    var currentBankId = $(this).data('id');

    $.ajax({
        url: '/chite-fund/delete-bank-detail/' + currentBankId,
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success
            console.log(response.message);
            Swal.fire({
                title: 'Success!',
                text: 'Bank detail deleted successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 1000);
            // Optionally, you can redirect or update the UI as needed
        },
        error: function(error) {
            // Handle error
            console.error('Error deleting bank detail:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to delete bank detail. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
