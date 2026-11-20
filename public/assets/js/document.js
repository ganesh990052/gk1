// Handle "Add" button click
$('#openDocumentDetailModal').on('click', function() {
    // Clear previous data
    $('#adddocumentDetailForm')[0].reset();

    // Open the Bootstrap Modal
    $('#adddocumentModal').modal('show');

    // Fetch user information via AJAX and populate the select dropdown
    $.ajax({
        url: '/chite-fund/get-all-document-users',
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
$('#adddocumentDetailForm').submit(function(e) {
    e.preventDefault();

    // Check if required fields are filled
    if (!$('#user_id').val()) {
        // Show an error message or handle it as needed
        console.error('Please select a user.');
        return;
    }

    // Create a FormData object to handle file uploads
    var formData = new FormData($(this)[0]);

    // Make AJAX request to add a new document detail
    $.ajax({
        url: '/chite-fund/add-document-detail',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        data: formData,
        contentType: false, // Necessary when sending formData
        processData: false, // Necessary when sending formData
        success: function(response) {
            // Handle success
            console.log(response.message);

            // Close the Bootstrap Modal

            // Show success notification using SweetAlert
            Swal.fire({
                title: 'Success!',
                text: 'Document details inserted successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 1000);
            $('#add-document-modal').modal('hide');

            // Optionally, you can update the UI or perform other actions
        },
        error: function(error) {
            // Handle error
            console.error('Error adding document detail:', error);
        }
    });
});



$(document).ready(function() {
    var currentAddId;

    // DataTable initialization code
    $('#DataTable').DataTable();

    // Read button click event
    $('.document-details-btn-edit').on('click', function() {
        currentdocumentId = $(this).data('id');
        // Fetch existing data using AJAX and display it in the modal
        $.ajax({
            method: 'GET',
            url: '/chite-fund/get-document-detail/' + currentdocumentId,
            success: function(response) {
                var aadhar_card_fronturl = window.location.origin+'/storage/app/'+response.aadhar_card_front;
                var aadhar_card_fronturlWithoutAppPublic = aadhar_card_fronturl.replace('/app/public', '');

                $('#imgaadhar_card_front').attr('src', aadhar_card_fronturlWithoutAppPublic);


                var aadhar_card_backurl = window.location.origin+'/storage/app/'+response.aadhar_card_back;
                var aadhar_card_backurlWithoutAppPublic = aadhar_card_fronturl.replace('/app/public', '');

                $('#imgaadhar_card_back').attr('src', aadhar_card_backurlWithoutAppPublic);


                var pan_cardurl = window.location.origin+'/storage/app/'+response.pan_card;
                var pan_cardurlWithoutAppPublic = pan_cardurl.replace('/app/public', '');

                $('#imgpan_card').attr('src', pan_cardurlWithoutAppPublic);


                var bank_statementurl = window.location.origin+'/storage/app/'+response.bank_statement;
                var bank_statementurlWithoutAppPublic = bank_statementurl.replace('/app/public', '');

                $('#imgbank_statement').attr('src', bank_statementurlWithoutAppPublic);
                var salary_slipurl = window.location.origin+'/storage/app/'+response.salary_slip;
                var salary_slipurlWithoutAppPublic = salary_slipurl.replace('/app/public', '');

                $('#imgsalary_slip').attr('src', salary_slipurlWithoutAppPublic);

                // Display existing data in the Read modal
                $('#id').val(response.id);
                $('#editDocumentDetailModal').modal('show');
                $('#aadhar_card_front').val(response.aadhar_card_front);

                $('#aadhar_card_back').val(response.aadhar_card_back);
            
                $('#pan_card').val(response.pan_card);
                $('#imgpan_card').attr('src', 'storage/documents/'+response.pan_card);

                $('#bank_statement').val(response.bank_statement);

                $('#salary_slip').val(response.salary_slip);


                
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
    $(document).ready(function() {
        $('#aadhar_card_front, #aadhar_card_back, #pan_card, #bank_statement, #salary_slip').change(function() {
            var inputId = $(this).attr('id');
            previewImage(inputId);
        });
    
        function previewImage(inputId) {
            var fileInput = $('#' + inputId);
            var previewImageId = 'img' + inputId;
    
            var file = fileInput[0].files[0];
    
            if (file) {
                var reader = new FileReader();
    
                reader.onload = function(e) {
                    $('#' + previewImageId).attr('src', e.target.result);
                };
    
                reader.readAsDataURL(file);
            }
        }
    
        $('#document-btn-save-edit').on('click', function() {
            var currentaddId = $('#id').val();
    
            var formData = new FormData($('#editFileForm')[0]);
    
            $.ajax({
                url: '/chite-fund/update-document-detail/' + currentaddId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Handle success
                    console.log(response.message);
    
                    // Show success notification with Swal
                    Swal.fire({
                        title: 'Success!',
                        text: 'Document details updated successfully',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(error) {
                    // Handle error
                    console.error('Error updating document details:', error);
    
                    // Show error notification with Swal
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update document details. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
    
});

// Assuming you have a button or event that triggers the deletion
$('.document-details-btn-link').on('click', function() {
    var currentId = $(this).data('id');

    $.ajax({
        url: '/chite-fund/delete-document-detail/' + currentId,
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success
            console.log(response.message);
            Swal.fire({
                title: 'Success!',
                text: 'Document  deleted successfully',
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
            console.error('Error Document  detail:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to delete Document. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});

    $(document).ready(function () {
        // Button click event to close any popup
        $('.btn-close').on('click', function () {
            $('.popup').hide(); // or $('.popup').fadeOut();
        });
    });
    $(document).ready(function() {
        $('#aadhar_card_front, #aadhar_card_back, #pan_card, #bank_statement, #salary_slip').change(function() {
            var inputId = $(this).attr('id');
            previewImage(inputId);
        });
    
        function previewImage(inputId) {
            var fileInput = $('#' + inputId);
            var previewImageId = 'preview' + inputId.charAt(0).toUpperCase() + inputId.slice(1);
    
            var file = fileInput[0].files[0];
    
            if (file) {
                var reader = new FileReader();
    
                reader.onload = function(e) {
                    $('#' + previewImageId).attr('src', e.target.result);
                };
    
                reader.readAsDataURL(file);
            }
        }
    
        // Additional logic for saving or processing the form data can be added here
        $('#btnSaveEdit').click(function() {
            // Add logic to save or process the form data
        });
    });
    