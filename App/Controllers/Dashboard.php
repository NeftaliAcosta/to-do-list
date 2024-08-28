<?php

namespace App\Controllers;

use App\Core\CoreException;
use App\Core\SystemSession;
use App\Models\Task\Task;
use App\Models\User\Exception\UserNotFoundException;

class Dashboard
{
    /**
     * @return array
     * @throws CoreException
     * @throws UserNotFoundException
     */
    public function getDashboardInformation(): array {
        $response = [];

        // Get session information
        $oSession = new SystemSession();
        $userId = $oSession->get('id');

        $oUser = new \App\Models\User\User($userId);
        $oTask = new Task();
        $response['name'] = $oUser->getName();
        $response['email'] = $oUser->getEmail();
        $response['registrationDate'] = $oUser->getRegistrationDate();
        $response['tasks'] = array($oTask->getAll($oUser));

        return $response;
    }
}