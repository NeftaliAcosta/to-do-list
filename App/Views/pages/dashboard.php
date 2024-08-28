<?php

use App\Controllers\Dashboard;
use App\Core\CoreException;
use App\Core\SystemSession;
use App\Models\User\Exception\UserNotFoundException;
use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function logOut():void {
    header('Location: /signin');
    exit;
}

$oSession = new SystemSession();
$dashboardData = [];
if (!$oSession->validateLogin()) {
    logOut();
}

$oDashboard = new Dashboard();
try {
    $dashboardData = $oDashboard->getDashboardInformation();
    $tasksArray = $dashboardData['tasks'][0];
} catch (UserNotFoundException|CoreException $e) {
    logOut();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="text-center mb-4">Welcome <?= $dashboardData['name'] ?></h1>
            <p class="text-center">User since <?= $dashboardData['registrationDate'] ?></p>

            <h5 class="">My <b>To Do List</b></h5>
            <table class="table table-striped" id="tasks">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tasksArray as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task->title); ?></td>
                        <td><?php echo htmlspecialchars($task->description); ?></td>
                        <td>
                            <?php
                            $badgeClass = 'badge-secondary'; // Default class
                            if ($task->status === 1) {
                                $badgeClass = 'badge-warning';
                                $badgeText = 'Create';
                            } elseif ($task->status === 2) {
                                $badgeClass = 'badge-info';
                                $badgeText = 'In progress';
                            } elseif ($task->status === 3) {
                                $badgeClass = 'badge-success';
                                $badgeText = 'Success';
                            }
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>">
                                <?php echo htmlspecialchars($badgeText); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Actions">
                                <button type="button" class="btn btn-primary mr-1">View</button>
                                <button type="button" class="btn btn-secondary mr-1 ml-1">Edit</button>
                                <button type="button" class="btn btn-danger ml-1">Delete</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>