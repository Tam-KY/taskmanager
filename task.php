<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Manager</title>
    <link rel="stylesheet" href="style.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="navbar">
        <div><a href="index.php"><strong>Task Manager</strong></a></div>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <h2>Your Tasks</h2>

        <a href="create_task.php" class="button" id="create-task-btn">+ Create New Task</a>
        <br>
        <div id="task-list"></div>
    </div>

    <script>
        function loadTasks() {
            $.ajax({
                url: 'get_tasks.php',
                method: 'GET',
                success: function(data) {
                    $('#task-list').html(data);
                },
                error: function(xhr) {
                    console.error("AJAX Load Error:", xhr.responseText);
                }
            });
        }

        $(document).ready(function () {
            loadTasks(); 
            setInterval(loadTasks, 5000);
        });

        function markAsCompleted(taskId, button) {
            $.ajax({
                url: 'mark_complete.php',
                method: 'POST',
                data: { task_id: taskId },
                success: function(response) {
                    if (response.success) {
                        const row = button.closest('tr');
                        row.classList.add('completed');  
                        button.remove();                  
                        const completedLabel = document.createElement('span');
                        completedLabel.textContent = '✔ Completed';
                        row.querySelector('td.status').appendChild(completedLabel);
                    } else {
                        alert('Failed to mark as completed.');
                    }
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText);
                    alert('Something went wrong.');
                }
            });
        }

        function deleteTask(taskId) {
            if (confirm("Are you sure you want to delete this task?")) {
                $.ajax({
                    url: 'delete_task.php',
                    method: 'POST',
                    data: { task_id: taskId },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            alert(result.message);
                            loadTasks();  
                        } else {
                            alert(result.message);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                        alert('Something went wrong.');
                    }
                });
            }
        }

        function editTask(taskId, currentTaskName) {
            const newTaskName = prompt("Edit task name:", currentTaskName);
            if (newTaskName) {
                const newDueDate = prompt("Edit due date:", "YYYY-MM-DD");
                if (newDueDate) {
                    $.ajax({
                        url: 'edit_task.php',
                        method: 'POST',
                        data: { 
                            task_id: taskId, 
                            task_title: newTaskName, 
                            due_date: newDueDate    
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                alert(result.message);
                                loadTasks(); 
                            } else {
                                alert(result.message);
                            }
                        },
                        error: function(xhr) {
                            console.error("Error:", xhr.responseText);
                            alert('Something went wrong.');
                        }
                    });
                } else {
                    alert("Please provide a valid due date.");
                }
            } else {
                alert("Please provide a valid task title.");
            }
        }
    </script>
</body>
</html>