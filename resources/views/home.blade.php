<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ayaz Task</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="text-primary">Simple To Do List App</h3>
        <button class="btn btn-warning showall_task" id="show_all_task"><span style="color: #ffff">Show All Task</span></button>
        <button class="btn btn-info hide_done_task" id="hide_done_task" hidden><span style="color: #ffff">Hide Done Task</span></button>
    </div>

    <form id="add_task_form">
        <div class="input-group my-4">
            <input type="text" class="form-control name" placeholder="Enter Task" aria-label="Enter Task" required>
            <button class="btn btn-primary" type="submit">Add Task</button>
        </div>
    </form>
        
    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Task</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="show_data">
       
       
       
      </tbody>
    </table>
  </div>

  <script>

$('#add_task_form').submit(function (e) {
    e.preventDefault();
    var name = $('.name').val();
    
    if (name === '') {
        alert('Enter Task Name');
        return;
    }
    $.ajax({
        type: "GET",
        url: '{{route('add.task')}}',
        data: {
            'name': name,
        },
        success: function (response) {
            if (response.status === 201) {
                alert(response.message);
                return;
            }
            if (response.status === 200) {
                var newTask = response.data;
                var index = $('#show_data tr').length + 1;
                if (typeof newTask.task_status === 'undefined') {
                    newTask.task_status = 0;
                }

                var taskStatusBadge = newTask.task_status == 1 ?
                    '<span class="badge bg-success">Done</span>' :
                    '<span class="badge bg-danger">Pending</span>';

                var updateButton = '';
                if (newTask.task_status == 0) {
                    updateButton = `
                        <button class="btn btn-success btn-sm">
                            <i class="fa fa-check-square-o" style="background-color: green; color: white" onclick="UpdateTask(${newTask.id})"></i>
                        </button>
                    `;
                }
                var row = `
                    <tr id="key_${newTask.id}">
                        <td>${index}</td>
                        <td>${newTask.name}</td>
                        <td>${taskStatusBadge}</td>
                        <td>
                            ${updateButton}
                            <button class="btn btn-danger btn-sm" style="color: white;" onclick="Delete(${newTask.id})">X</button>
                        </td>
                    </tr>
                `;
                $('#show_data').append(row);
                $('.name').val('');
            } else {
                alert("Failed to add task");
            }
        },
        error: function (error) {
            alert(error.responseText || "An error occurred");
        }
    });
    });

    $(document).ready(function () {
        $.ajax({
            type: "GET",
            url: '{{route('get.todo')}}',
            success: function (response) {
                // console.log(response)
                var data = response.data;
                $('#show_data').empty();
                data.forEach(function (element, index) {
                var taskStatusBadge = element.task_status == 1 ? 
                    '<span class="badge bg-success">Done</span>' : 
                    '<span class="badge bg-danger">Pending</span>';
                var updateButton = '';
                    if (element.task_status == 0) {
                        updateButton = `
                            <button class="btn btn-success btn-sm">
                                <i class="fa fa-check-square-o" style="background-color: green; color: white" onclick="UpdateTask(${element.id})"></i>
                            </button>
                        `;
                    }
                var row = `
                    <tr id="key_${element.id}">
                        <td>${index + 1}</td>
                        <td>${element.name}</td>
                        <td>${taskStatusBadge}</td>
                        <td>${updateButton}
                            <button class="btn btn-danger btn-sm" onclick="Delete(${element.id})" style="color: white;">X</button>
                        </td>
                    </tr>
                `;
                $('#show_data').append(row);
            });

                
            },
            error:function(error){
                console.log(error)
            }

        });
    });

    function Delete(id){

     var isConfirmed = confirm("Are you sure you want to delete this task?");
     if(isConfirmed){
         $.ajax({
           type: "GET",
           url: '{{route('delete')}}',
           data: {
               'id':id
           },
           success: function (response) {
               if(response.status==200){
                   $('#key_'+id).hide();
                   alert(response.message)
   
               }
               else{
                   alert(response.message);
               }
               
           },
           error:function(error){
               console.log(error)
           }
         });
     }
     else{
        alert('Task Delete Cancel')
     }
    }

    function UpdateTask(id){

       $.ajax({
        type: "GET",
        url: '{{route('update.task')}}',
        data: {
            'id':id,
        },
        success: function (response) {
            if(response.status==200){
                $('#key_'+id).hide();
                   alert(response.message)
   
               }
               else{
                   alert(response.message);
               }
            
            
        },
        error:function(error){
               console.log(error)
           }
       });
    }

    $('.showall_task').click(function (e) { 
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: '{{route('show.alltask')}}',
            success: function (response) {
                console.log(response)
                if(response.status == 200) {
                $('#show_all_task').attr('hidden', true);
                $('#hide_done_task').removeAttr('hidden');
                var tasks = response.data;
                $('#show_data').empty();
                tasks.forEach(function (task, index) {
                    var taskStatusBadge = task.task_status == 1 ? 
                        '<span class="badge bg-success">Done</span>' : 
                        '<span class="badge bg-danger">Pending</span>';
                    var updateButton = '';
                    if (task.task_status == 0) {
                        updateButton = `
                            <button class="btn btn-success btn-sm">
                                <i class="fa fa-check-square-o" style="background-color: green; color: white" onclick="UpdateTask(${task.id})"></i>
                            </button>
                        `;
                    }
                    var row = `
                        <tr id="key_${task.id}">
                            <td>${index + 1}</td>
                            <td>${task.name}</td>
                            <td>${taskStatusBadge}</td>
                            <td>
                                ${updateButton}
                                <button class="btn btn-danger btn-sm" style="color: white;" onclick="Delete(${task.id})">X</button>
                            </td>
                        </tr>
                    `;
                    $('#show_data').append(row);
                });
            } else {
                alert("Failed to load tasks");
            }
                
            },
            error:function(error){
               console.log(error)
           }
        });
        
    });
    $('.hide_done_task').click(function (e) { 
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: '{{route('get.todo')}}',
            success: function (response) {
                // console.log(response)
                $('#show_all_task').removeAttr('hidden');
                $('#hide_done_task').attr('hidden', true);
                var data = response.data;
                $('#show_data').empty();
                data.forEach(function (element, index) {
                var taskStatusBadge = element.task_status == 1 ? 
                    '<span class="badge bg-success">Done</span>' : 
                    '<span class="badge bg-danger">Pending</span>';
                var updateButton = '';
                    if (element.task_status == 0) {
                        updateButton = `
                            <button class="btn btn-success btn-sm">
                                <i class="fa fa-check-square-o" style="background-color: green; color: white" onclick="UpdateTask(${element.id})"></i>
                            </button>
                        `;
                    }
                var row = `
                    <tr id="key_${element.id}">
                        <td>${index + 1}</td>
                        <td>${element.name}</td>
                        <td>${taskStatusBadge}</td>
                        <td>${updateButton}
                            <button class="btn btn-danger btn-sm" onclick="Delete(${element.id})" style="color: white;">X</button>
                        </td>
                    </tr>
                `;
                $('#show_data').append(row);
            });

                
            },
            error:function(error){
                console.log(error)
            }

        });
        
    });

  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
