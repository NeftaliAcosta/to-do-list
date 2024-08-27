<?php

// Init local variables
use App\Controllers\User;

$showError = false;
$messageError = '';
$messageSuccess = false;

// Validating method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $oUser = new User();
    $response = $oUser->signUp($_POST);
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
            <h2 class="text-center mb-4">Sign up</h2>
            <div class="alert alert-danger mt-5 <?php echo $showError ? '' : 'd-none'; ?>" role="alert">
                Unable to create user, please validate the following.
                <?php echo $messageError; ?>
            </div>

            <div class="alert alert-success <?php echo $messageSuccess ? '' : 'd-none'; ?>" role="alert">
                User created successfully.
            </div>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Your name here" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Your email here" required>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Your password here" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
        </div>
    </div>
</div>