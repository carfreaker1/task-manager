$(function () {
    bsCustomFileInput.init();
});

$(function () {

    $('#quickForm').validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 5
            },
            terms: {
                required: true
            },
        },
        messages: {
            email: {
                required: "Please enter a email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            terms: "Please accept our terms"
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
$(function () {
    $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
});

$.widget.bridge('uibutton', $.ui.button)

document.addEventListener("DOMContentLoaded", function() {
    document.querySelector("form").addEventListener("submit", function(event) {
        var departmentSelect = document.getElementById("department");
        var departmentError = document.getElementById("departmentError");

        if (departmentSelect.value === "") {
            departmentError.style.display = "block";
            event.preventDefault(); // Prevent form submission
        } else {
            departmentError.style.display = "none";
        }
    });
});


    document.addEventListener('DOMContentLoaded', function() {
        var userDesignationId = $user=>designation_id;  // Replace this with the actual variable holding the current user's designation ID

        document.getElementById('department').addEventListener('change', function() {
            var departmentId = this.value;
            var designationSelect = document.getElementById('designation');

            // Clear previous options
            designationSelect.innerHTML = '<option value="">--- Select Your Designation ---</option>';

            // Fetch designations related to the selected department
            fetch('/fetch-designations/' + departmentId)
                .then(response => response.json())
                .then(data => {
                    // Populate the Designation select element with the fetched data
                    data.forEach(designation => {
                        var option = document.createElement('option');
                        option.value = designation.id;
                        option.text = designation.designation_name;

                        // Check if the current user's designation ID matches the option's value
                        if (designation.id === userDesignationId) {
                            option.selected = true; // Set the selected attribute
                        }

                        designationSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching designations:', error));
        });
    });




