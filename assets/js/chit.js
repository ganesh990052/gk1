$('#openchitsModal').on('click', function() {
    $('#chitForm')[0].reset();

    $('#chitModal').modal('show');

 
});
$(document).ready(function(){
    // Function to add more input fields for presentage
    $('#addMore').click(function(){
        var presentageValue = $('#presentageValue').val().trim(); // Trim leading and trailing spaces
        if (presentageValue === '') return; // Ignore if input is empty

        var updatedValue = $('#valueofprofit').val();
        if (updatedValue) {
            updatedValue += ', ';
        }
        updatedValue += presentageValue;
        $('#valueofprofit').val(updatedValue);
      
        
       
    });
   
});
$('#chitForm').submit(function(e) {
    e.preventDefault();

    var presentageValue = $('#valueofprofit').val().trim(); 
            
    var digitsCount = presentageValue.split(',').length;

    var duration = parseInt($('#add_duration').val()); 
    $.ajax({
        url: '/chite-fund/chit/add',
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
                text: 'Chit Insert successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 1000);
            $('#chitModal').modal('hide');

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
    $('.chit-details-btn-edit').on('click', function() {
        currentChitId = $(this).data('id');
        // Fetch existing data using AJAX and display it in the modal
        $.ajax({
            method: 'GET',
            url: '/chite-fund/chit/edit/' + currentChitId,
            success: function(response) {
                // Display existing data in the Read modal
                $('#id').val(response.id);
                $('#editchitModal').modal('show');
                $('#chit_name').val(response.name);
                $('#chit_value').val(response.chitdetail[0].chit_value);
                $('#duration').val(response.chitdetail[0].duration);
                $('#subscription_amount').val(response.chitdetail[0].subscription_amount);
                $('#first_auction_date').val(response.chitdetail[0].first_auction_date);
                $('#chit_type').val(response.chitdetail[0].chit_type);


                
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
    $('#btnchitEdit').on('click', function() {
        var currentchitId = $('#id').val();

    $.ajax({
        url: '/chite-fund/chit/update/' + currentchitId,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       
        data: {
            name: $('#chit_name').val(),
            chit_value: $('#chit_value').val(),
            duration: $('#duration').val(),
            first_auction_date: $('#first_auction_date').val(),
            chit_type: $('#chit_type').val(),
            subscription_amount: $('#subscription_amount').val(),

            // Add any other fields you want to update
        },
        success: function(response) {
            // Handle success
            console.log(response.message);

            // Show success notification with Swal
            Swal.fire({
                title: 'Success!',
                text: 'Chit details updated successfully',
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
$('.chit-details-btn-link').on('click', function() {
    var currentchitId = $(this).data('id');

    $.ajax({
        url: '/chite-fund/chit/delete/' + currentchitId,
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success
            console.log(response.message);
            Swal.fire({
                title: 'Success!',
                text: 'Chit detail deleted successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            // setTimeout(function() {
            //     location.reload();
            // }, 1000);
            // Optionally, you can redirect or update the UI as needed
        },
        error: function(error) {
            // Handle error
            console.error('Error chit  detail:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to delete chit detail. Please try again.',
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




// monthly Winner



// Handle "Add" button click
$('#openAddMontlyWinerModal1').on('click', function() {
    // Clear previous data
    $('#addMonthlyWinnerForm')[0].reset();

    // Open the Bootstrap Modal
    $('#addMonthlyWinnerModal').modal('show');

    // Fetch user information via AJAX and populate the select dropdown
    $.ajax({
        url: '/chite-fund/get-all-users',
        method: 'GET',
        success: function(response) {
            // Populate the select dropdown with user options
            $('#user_id').html('<option value="" disabled selected>Select User</option>');
            $.each(response.users, function(index, user) {
                $('#user_id').append('<option value="' + user.id + '">' + user.email + '</option>');
            });
        },
        error: function(error) {
            console.error('Error fetching user information:', error);
        }
    });
});

$('#addMonthlyWinnerForm').submit(function(e) {
    e.preventDefault();

    // Check if required fields are filled
    if (!$('#user_id').val()) {
        // Show an error message or handle it as needed
        console.error('Please select a user.');
        return;
    }

    // Make AJAX request to add a new bank detail
    $.ajax({
        url: '/chite-fund/monthly-lucky-member',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            // Handle success
            console.log(response.message);
    
            // Close the Bootstrap Modal
            $('#addMonthlyWinnerModal').modal('hide');
    
            // Show success notification using SweetAlert
            Swal.fire({
                title: 'Success!',
                text: 'Monthly Winner inserted successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 1000);

            // Optionally, you can update the UI or perform other actions
        },
        error: function(error) {
            // Handle error
            console.error('Error adding Monthly Winner detail:', error);
            Swal.fire({
                title: 'Error!',
                text: 'A winner has already been selected for this month',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            
        }
    });
});

// Assuming you have a button or event that triggers the deletion
$('.monthly-winner-btn-link').on('click', function() {
    var currentBankId = $(this).data('id');

    $.ajax({
        url: '/chite-fund/delete-monthly-winner/' + currentBankId,
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success
            console.log(response.message);
            Swal.fire({
                title: 'Success!',
                text: 'Monthly Winner deleted successfully',
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
            console.error('Error deleting Monthly Winner:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to delete Monthly Winner. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});


