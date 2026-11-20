

$(document).ready(function() {
    var currentAddId;

    // DataTable initialization code
    $('#DataTable').DataTable();

    // Read button click event
    $('.transfer').on('click', function() {
        currentuserId = $(this).data('id');
        // Fetch existing data using AJAX and display it in the modal
        $.ajax({
            method: 'GET',
            url: '/chite-fund/user-bank-detail/' + currentuserId,
            success: function(response) {
                // Display existing data in the Read modal
                $('#id').val(response.user_id);

                $('#edittranserModal').modal('show');
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

    $('#transfer_mony').on('click', function() {
        var currentuserId = $('#id').val();

    $.ajax({
        url: '/chite-fund/transer-mony/',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       
        data: {
            amount: $('#amount').val(),
            user_id:currentuserId,
           

            // Add any other fields you want to update
        },
        success: function(response) {
            // Handle success
            console.log(response.message);

            // Show success notification with Swal
            Swal.fire({
                title: 'Success!',
                text: 'Transfer Successfully',
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