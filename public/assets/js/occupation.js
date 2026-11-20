// Handle "Add" button click
$('#openAddOccupationDetailModal').on('click', function() {
    // Clear previous data
    $('#addOccupationDetailForm')[0].reset();

    // Open the Bootstrap Modal
    $('#addOccupationModal').modal('show');

    // Fetch user information via AJAX and populate the select dropdown
    $.ajax({
        url: '/chite-fund/get-all-occupation-users',
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
$('#addOccupationDetailForm').submit(function(e) {
    e.preventDefault();

    // Check if required fields are filled
    if (!$('#user_id').val()) {
        // Show an error message or handle it as needed
        console.error('Please select a user.');
        return;
    }

    // Make AJAX request to add a new bank detail
    $.ajax({
        url: '/chite-fund/add-occupation-detail',
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
                text: 'Occupation details Insert successfully',
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
            console.error('Error occupation detail:', error);
        }
    });
    
});


$(document).ready(function() {
    var currentBankId;

    // DataTable initialization code
    $('#DataTable').DataTable();

    // Read button click event
    $('.occupation-details-btn-edit').on('click', function() {
        currentId = $(this).data('id');
        // Fetch existing data using AJAX and display it in the modal
        $.ajax({
            method: 'GET',
            url: '/chite-fund/get-occupation-detail/' + currentId,
            success: function(response) {
                // Display existing data in the Read modal
                $('#id').val(response.id);
                $('#editOccupationModal').modal('show');
                $('#occupation').val(response.occupation);
                $('#current_employer').val(response.current_employer);
                $('#years_at_current_employer').val(response.years_at_current_employer);
                $('#monthly_income').val(response.monthly_income);
                $('#living_status').val(response.living_status);
                $('#years_at_current_residence').val(response.years_at_current_residence	);

                
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
    $('#btnSaveoccupation').on('click', function() {
        var currentBankId = $('#id').val();

    $.ajax({
        url: '/chite-fund/update-occupation-detail/' + currentBankId,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            occupation: $('#occupation').val(),
            bank_name: $('#editedBankName').val(),
            current_employer: $('#current_employer').val(),
            years_at_current_employer: $('#years_at_current_employer').val(),
            monthly_income: $('#monthly_income').val(),
            living_status: $('#living_status').val(),
            years_at_current_residence: $('#years_at_current_residence').val(),

            // Add any other fields you want to update
        },
        success: function(response) {
            // Handle success
            console.log(response.message);

            // Show success notification with Swal
            Swal.fire({
                title: 'Success!',
                text: 'Occupation details updated successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 1000);
        },
        error: function(error) {
            // Handle error
            console.error('Error updating occupation details:', error);

            // Show error notification with Swal
            Swal.fire({
                title: 'Error!',
                text: 'Failed to update occupation details. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
           
        }
        });
    });
});

// Assuming you have a button or event that triggers the deletion
$('.occupation-details-btn-link').on('click', function() {
    var currentBankId = $(this).data('id');

    $.ajax({
        url: '/chite-fund/delete-occupation-detail/' + currentBankId,
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success
            console.log(response.message);
            Swal.fire({
                title: 'Success!',
                text: 'occupation detail deleted successfully',
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
