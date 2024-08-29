<?php

use App\Controllers\Task;
use App\Core\SystemSession;
use JetBrains\PhpStorm\NoReturn;

$showError = false;
$messageError = '';
$messageSuccess = false;

#[NoReturn] function logOut():void {
    header('Location: /signin');
    exit;
}

$oSession = new SystemSession();
$dashboardData = [];
if (!$oSession->validateLogin()) {
    logOut();
}

// Getting information about it
$oTask = new Task();
$response =  $oTask->taskView($uuid);
if ($response['showError']) {
    header('Location: /dashboard');
}
$task = $response['task'];
?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <a href="/dashboard/task/edit/<?= $task->getUuid() ?>"><button type="button" class="btn btn-primary float-right mb-2">Edit</button></a>
                <h2 class="text-center mb-4">View task</h2>
                <div class="alert alert-danger mt-5 <?php echo $showError ? '' : 'd-none'; ?>" role="alert">
                    Unable to create user, please validate the following.
                    <?php echo $messageError; ?>
                </div>

                <div class="alert alert-success <?php echo $messageSuccess ? '' : 'd-none'; ?>" role="alert">
                    Task created successfully.
                </div>

                <form method="POST" action="<?php echo $_SERVER['REQUEST_URI'];?>">
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="<?= $task->getTitle() ?? '' ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?= $task->getDescription() ?? '' ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select class="custom-select custom-select-lg mb-3" id="status" name="status" disabled>
                            <option value="1" <?= $task->getStatus() == 1 ? 'selected' : ''  ?>>Create</option>
                            <option value="2" <?= $task->getStatus() == 2 ? 'selected' : ''  ?>>In progress</option>
                            <option value="3" <?= $task->getStatus() == 3 ? 'selected' : ''  ?>>Success</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php if ($messageSuccess): ?>
    <script>
        setTimeout(function() {
            window.location.href = '/dashboard';
        }, 1000);
    </script>
<?php endif; ?>