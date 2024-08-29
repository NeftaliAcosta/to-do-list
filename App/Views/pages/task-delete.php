<?php

use App\Controllers\Task;
use App\Core\SystemSession;
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

// Getting information about it and validate if it exists
$oTask = new Task();
$response =  $oTask->taskView($uuid);
if ($response['showError']) {
    header('Location: /dashboard');
}

// Deleting task
$oTask = new Task();
$response =  $oTask->taskDelete($uuid);
$showError = $response['showError'];
$messageError = $response['messageError'];

if (!$showError) {
    header('Location: /dashboard');
}
?>

<?php if ($showError): ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4"><?= $messageError ?></h2>
        </div>
    </div>
</div>
<?php endif; ?>