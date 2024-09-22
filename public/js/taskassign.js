$(document).ready(function() {
    // Initialize DataTable
    $('#example1').dataTable();

    // Event listener for clicking on "#assign_employees" button
    $("#assign_employees").click(function(){
        var taskId = [];               

        $("#taks_id").empty();

        // Iterate over each checked checkbox with class ".select-doctor"
        $(".select-doctor:checked").each(function(){
            taskId.push($(this).val());
            // You can uncomment and add more data to taskId array if needed
            // email.push($(this).attr('data-email'));
            // name.push($(this).attr('data-name'));
        });
// console.log(taskId);
        // Check if any task is selected
        if(taskId.length > 0){
            // Append hidden input field containing selected task IDs to "#taks_id" element 
            $("#taks_id").append('<input type="hidden" id="selected_task_id" value="'+taskId+'" name="assigned_task">');
            // Show the modal
            $("#modal-lg").modal('show');
        } else {
            // Show alert if no task is selected
            alert("Please select at least one Task.");
            // Hide the modal
            $("#modal-lg").modal('hide');
        }
    });
});
