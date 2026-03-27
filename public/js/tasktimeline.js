$(document).ready(function() {
    // Initialize DataTable
    $('#example1').dataTable();

    // Event listener for clicking on ".task_timeline" button for editing
    $(document).on('click', '.edit_task_timeline', function() {
        var taskId = $(this).val();
        var taskData = $(this).data('task'); // Assuming you're storing additional data in data attributes

        // Empty the assigned_task container
        $("#assigned_task_edit").empty();

        // Append hidden input field containing selected task ID to "#assigned_task_edit" element
        $("#assigned_task_edit").append('<input type="hidden" id="selected_task_id" value="'+ taskId +'" name="assigned_task">');

        // Populate the form fields with existing data
        $('input[name="task_status_id"]').val(taskData.id);
        $('input[name="start_date"]').val(taskData.start_date);
        $('input[name="end_date"]').val(taskData.start_date);
        $('input[name="sub_module"]').val(taskData.sub_module);
        $('textarea[name="summary"]').val(taskData.summary);
        $('input[name="functionality"]').val(taskData.functionality);
        $('select[name="task_status"]').val(taskData.task_status);

        // Show the edit modal
        $("#modal-edit").modal('show');
    });

    // Event listener for clicking on ".create_task_timeline" button for creating
    $(document).on('click', '.task_timeline', function() {
        var taskId = $(this).val();

        // Empty the assigned_task container
        $("#assigned_task_create").empty();

        // Append hidden input field containing selected task ID to "#assigned_task_create" element
        $("#assigned_task_create").append('<input type="hidden" id="selected_task_id" value="'+ taskId +'" name="assigned_task">');

        // Clear the form fields
        $('#quickFormCreate').trigger("reset");

        // Show the create modal
        $("#modal-create").modal('show');
    });
});
