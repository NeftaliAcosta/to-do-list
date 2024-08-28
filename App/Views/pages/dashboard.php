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
} catch (UserNotFoundException|CoreException $e) {
    logOut();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="text-center mb-4">Wellcome <?= $dashboardData['name'] ?></h1>
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
                <tr>
                    <td>Revisar correos</td>
                    <td>Revisar y responder todos los correos pendientes del día.</td>
                    <td><span class="badge badge-warning">Pendiente</span></td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Actions">
                            <button type="button" class="btn btn-primary mr-1">View</button>
                            <button type="button" class="btn btn-secondary mr-1 ml-1">Edit</button>
                            <button type="button" class="btn btn-danger ml-1">Delete</button>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td>Desarrollo del módulo de usuario</td>
                    <td>Implementar las funciones de creación, edición y eliminación de usuarios.</td>
                    <td><span class="badge badge-info">En progreso</span></td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Actions">
                            <button type="button" class="btn btn-primary mr-1">View</button>
                            <button type="button" class="btn btn-secondary mr-1 ml-1">Edit</button>
                            <button type="button" class="btn btn-danger ml-1">Delete</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Reunión con el equipo</td>
                    <td>Asistir a la reunión de planificación semanal con el equipo de desarrollo.</td>
                    <td><span class="badge badge-success">Completada</span></td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Actions">
                            <button type="button" class="btn btn-primary mr-1">View</button>
                            <button type="button" class="btn btn-secondary mr-1 ml-1">Edit</button>
                            <button type="button" class="btn btn-danger ml-1">Delete</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>