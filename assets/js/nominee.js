// Handle "Add" button click
$('#openAddNomineeDetailModal').on('click', function() {
    // Clear previous data
    $('#addNomineeDetailForm')[0].reset();

    // Open the Bootstrap Modal
    $('#addNomineeModal').modal('show');

    // Fetch user information via AJAX and populate the select dropdown
    $.ajax({
        url: '/chite-fund/get-all-nominee-users',
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
$('#addNomineeDetailForm').submit(function(e) {
    e.preventDefault();

    // Check if required fields are filled
    if (!$('#user_id').val()) {
        // Show an error message or handle it as needed
        console.error('Please select a user.');
        return;
    }

    // Make AJAX request to add a new bank detail
    $.ajax({
        url: '/chite-fund/add-nominee-detail',
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
                text: 'Nominee details Insert successfully',
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
            console.error('Error Nominee detail:', error);
        }
    });
    
});


$(document).ready(function() {
    var currentBankId;

    // DataTable initialization code
    $('#DataTable').DataTable();

    // Read button click event
    $('.nominee-details-btn-edit').on('click', function() {
        currentId = $(this).data('id');
        // Fetch existing data using AJAX and display it in the modal
        $.ajax({
            method: 'GET',
            url: '/chite-fund/get-nominee-detail/' + currentId,
            success: function(response) {
                // Display existing data in the Read modal
                $('#id').val(response.id);
                $('#editNomineeModal').modal('show');
                $('#nominee_name').val(response.nominee_name);
                $('#relation').val(response.relation);
                $('#contact_number').val(response.contact_number);
             
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
    $('#btnSavenominee').on('click', function() {
        var currentBankId = $('#id').val();

    $.ajax({
        url: '/chite-fund/update-nominee-detail/' + currentBankId,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            nominee_name: $('#nominee_name').val(),
            relation: $('#relation').val(),
            contact_number: $('#contact_number').val(),
       

            // Add any other fields you want to update
        },
        success: function(response) {
            // Handle success
            console.log(response.message);

            // Show success notification with Swal
            Swal.fire({
                title: 'Success!',
                text: 'Nominee details updated successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 1000);
        },
        error: function(error) {
            // Handle error
            console.error('Error updating nominee details:', error);

            // Show error notification with Swal
            Swal.fire({
                title: 'Error!',
                text: 'Failed to update nominee details. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
           
        }
        });
    });
});

// Assuming you have a button or event that triggers the deletion
$('.nominee-details-btn-link').on('click', function() {
    var currentBankId = $(this).data('id');

    $.ajax({
        url: '/chite-fund/delete-nominee-detail/' + currentBankId,
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success
            console.log(response.message);
            Swal.fire({
                title: 'Success!',
                text: 'Nominee detail deleted successfully',
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
            console.error('Error deleting nominee detail:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to delete nominee detail. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
$(document).ready(function(){
    $("#close").click(function(){
            $('#addNomineeDetailForm')[0].reset();

        $('.modal.show .modal-dialog').hide();

    });
});