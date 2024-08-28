<?php

namespace App\Controllers;

use App\Core\CoreException;
use App\Core\SystemSession;
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
        $response['name'] = $oUser->getName();
        $response['email'] = $oUser->getEmail();
        $response['registrationDate'] = $oUser->getRegistrationDate();

        return $response;
    }
}