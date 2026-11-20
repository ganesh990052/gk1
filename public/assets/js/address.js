// Handle "Add" button click
$('#open-Add-Address-DetailModal').on('click', function() {
    // Clear previous data
    $('#add-address-DetailForm')[0].reset();

    // Open the Bootstrap Modal
    $('#add-address-modal').modal('show');

    // Fetch user information via AJAX and populate the select dropdown
    $.ajax({
        url: '/chite-fund/get-all-address-users',
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
$('#add-address-DetailForm').submit(function(e) {
    e.preventDefault();

    // Check if required fields are filled
    if (!$('#user_id').val()) {
        // Show an error message or handle it as needed
        console.error('Please select a user.');
        return;
    }

    // Make AJAX request to add a new bank detail
    $.ajax({
        url: '/chite-fund/add-address-detail',
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
                text: 'Address details Insert successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 1000);
            $('#add-address-modal').modal('hide');

            // Optionally, you can update the UI or perform other actions
        },
        error: function(error) {
            // Handle error
            console.error('Error adding address detail:', error);
        }
    });
    
});


$(document).ready(function() {
    var currentAddId;

    // DataTable initialization code
    $('#DataTable').DataTable();

    // Read button click event
    $('.address-details-btn-edit').on('click', function() {
        currentAddId = $(this).data('id');
        // Fetch existing data using AJAX and display it in the modal
        $.ajax({
            method: 'GET',
            url: '/chite-fund/get-address-detail/' + currentAddId,
            success: function(response) {
                // Display existing data in the Read modal
                $('#id').val(response.id);
                $('#editAddressModal').modal('show');
                $('#house_no').val(response.house_no);
                $('#building_name').val(response.building_name);
                $('#street_name').val(response.street_name);
                $('#city').val(response.city);
                $('#state').val(response.state);
                $('#postal_code').val(response.postal_code);
                $('#landmark').val(response.landmark);


                
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
    $('#address-btn-save-edit').on('click', function() {
        var currentaddId = $('#id').val();

    $.ajax({
        url: '/chite-fund/update-address-detail/' + currentaddId,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            house_no: $('#house_no').val(),
            building_name: $('#building_name').val(),
            street_name: $('#street_name').val(),
            city: $('#city').val(),
            state: $('#state').val(),
            postal_code: $('#postal_code').val(),
            landmark: $('#landmark').val(),

            // Add any other fields you want to update
        },
        success: function(response) {
            // Handle success
            console.log(response.message);

            // Show success notification with Swal
            Swal.fire({
                title: 'Success!',
                text: 'Address details updated successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 1000);
        },
        error: function(error) {
            // Handle error
            console.error('Error updating address details:', error);

            // Show error notification with Swal
            Swal.fire({
                title: 'Error!',
                text: 'Failed to update address details. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
           
        }
        });
    });
});

// Assuming you have a button or event that triggers the deletion
$('.address-details-btn-link').on('click', function() {
    var currentaddId = $(this).data('id');

    $.ajax({
        url: '/chite-fund/delete-address-detail/' + currentaddId,
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success
            console.log(response.message);
            Swal.fire({
                title: 'Success!',
                text: 'Address detail deleted successfully',
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
            console.error('Error address  detail:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to delete address detail. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});

$(document).ready(function () {
    // Button click event to close any popup
    $('.btn-close, .close').on('click', function () {
        $('.popup').hide(); // or $('.popup').fadeOut();
    });
});
