<?php

namespace App\Controllers;

use App\Core\CoreException;
use App\Libs\Tools;
use App\Libs\Validator;
use Exception;
use Exception\UserCannotBeCreatedException;

class User
{
    /**
     * It is used to create a new user in the system
     */
    public function signUp(array $post): array
    {
        $oValidator = new Validator();
        $response['showError'] = false;
        $response['messageError'] = '';

        // Validating input data
        $oValidator->name('name')->value($post['name'])->isAlpha()->required();
        $oValidator->name('email')->value($post['email'])->isEmail()->required();
        $oValidator->name('password')->value($post['password'])->lengthMin(8)->lengthMax(20)
            ->isPassword()->required();

        // Processing information or showing errors messages
        if ($oValidator->isSuccess()) {
            try {
                $oUser = new  \App\Models\User\User();
                $oUser->setName($post['name'])
                    ->setEmail($post['email'])
                    ->setPassword(Tools::hash($post['password']))
                    ->create($oUser);
            } catch (Exception | UserCannotBeCreatedException $e) {
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