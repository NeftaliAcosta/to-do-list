<?php

namespace App\Controllers;

use App\Core\CoreException;
use App\Libs\Tools;
use App\Libs\Validator;
use App\Models\User\Exception;
use App\Models\User\Exception\UserCannotBeCreatedException;
class User
{
    /**
     * It is used to create a new user in the system
     *
     * @param array $post
     * @return array
     * @throws CoreException
     * @throws Exception\UserCannotBeCreatedException
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
            } catch (UserCannotBeCreatedException $e) {
                $response['showError'] = true;
                $response['messageError'] = $e->getMessage();
            }
        } else {
            $response['showError'] = true;
            $response['messageError'] = "<ul>{$oValidator->getErrorsHTML()}</ul>";
        }

        return $response;
    }

    /**
     * It is used to log in the system
     *
     * @param array $post
     * @return array
     * @throws CoreException
     */
    public function signIn(array $post): array
    {
        $oValidator = new Validator();
        $response['showError'] = false;
        $response['messageError'] = '';

        // Validating input data
        $oValidator->name('email')->value($post['email'])->isEmail()->required();

        // Processing information or showing errors messages
        if ($oValidator->isSuccess()) {
           try {
               $oUser = new  \App\Models\User\User();
               $oUser = $oUser->findByEmail($post['email']);
               if (Tools::hashVerify($post['password'], $oUser->getPassword())) {
                   $response['showError'] = false;
               } else {
                   $response['showError'] = true;
                   $response['messageError'] = "<ul><li>Incorrect username or password</li></ul>";
               }
           } catch (Exception\UserNotFoundException $e) {
               $response['showError'] = true;
               $response['messageError'] = "<ul><li>Incorrect username or password</li></ul>";
           }
        } else {
            $response['showError'] = true;
            $response['messageError'] = "<ul>{$oValidator->getErrorsHTML()}</ul>";
        }

        return $response;
    }
}