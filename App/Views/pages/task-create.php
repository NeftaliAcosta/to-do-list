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

// Validating method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oTask = new Task();
    $response =  $oTask->taskCreate($_POST);
    $showError = $response['showError'];
    $messageError = $response['messageError'];

    if (!$showError) {
        $messageSuccess = true;
    }
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">New task</h2>
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
                    <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="<?= $_POST['title'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description *</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?= $_POST['description'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status *</label>
                    <select class="custom-select custom-select-lg mb-3" id="status" name="status" required>
                        <option selected value="">Select a option</option>
                        <option value="1" <?= (isset($_POST['status']) && $_POST['status'] == 1) ? 'selected' : ''; ?>>Create</option>
                        <option value="2" <?= (isset($_POST['status']) && $_POST['status'] == 2) ? 'selected' : ''; ?>>In progress</option>
                        <option value="3" <?= (isset($_POST['status']) && $_POST['status'] == 3) ? 'selected' : ''; ?>>Success</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Create</button>
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