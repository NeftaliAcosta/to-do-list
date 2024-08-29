<?php

namespace App\Controllers;

use App\Core\CoreException;
use App\Core\SystemSession;
use App\Libs\Validator;
use App\Models\Task\Exception\TaskCannotBeCreatedException;
use App\Models\Task\Exception\TaskNotFoundException;

class Task
{
    /**
     * It is used to create a new task from front end
     *
     * @throws CoreException
     */
    public function taskCreate(array $post): array {
        $oValidator = new Validator();
        $response['showError'] = false;
        $response['messageError'] = '';

        // Validating input data
        $oValidator->name('title')->value($post['title'])->isAlpha()
            ->lengthMax(50)->required();
        $oValidator->name('description')->value($post['description'])->isAlphaNumeric()
            ->lengthMax(200)->required();
        $oValidator->name('status')->value($post['status'])->isInt()
            ->min(1)->max(3)->required();

        // Processing information or showing errors messages
        if ($oValidator->isSuccess()) {
            try {
                $oSession = new SystemSession();
                $userId = $oSession->get('id');

                $oTask = new \App\Models\Task\Task();
                $oTask->setTitle($post['title'])
                    ->setDescription($post['description'])
                    ->setStatus($post['status'])
                    ->setUserId($userId)
                    ->create($oTask);
                $response['showError'] = false;
            } catch (TaskCannotBeCreatedException $e) {
                $response['messageError'] = $e->getMessage();
            }

        } else {
            $response['showError'] = true;
            $response['messageError'] = "<ul>{$oValidator->getErrorsHTML()}</ul>";
        }


        return $response;
    }

    /**
     * It is used to show task in the front end
     *
     * @param string $uuid
     * @return array
     */
    public function taskView(string $uuid): array
    {
        $oValidator = new Validator();
        $response['showError'] = false;
        $response['messageError'] = '';

        // Validating input data
        $oValidator->name('uuid')->value($uuid)->isUuidValid()->required();

        // Processing information or showing errors messages
        if ($oValidator->isSuccess()) {
            try {
                $oTask = new \App\Models\Task\Task();
                $taskId = $oTask->getIdFromUuid($uuid);
                $oTask = new \App\Models\Task\Task($taskId);
                $response['task'] = $oTask;
            } catch (TaskNotFoundException $e) {
                $response['showError'] = true;
                $response['messageError'] = $e->getMessage();
            }
        } else {
            $response['showError'] = true;
            $response['messageError'] = "<ul>{$oValidator->getErrorsHTML()}</ul>";
        }

        return $response;
    }
}