<?php

namespace App\Models\User;

use App\Core\Container\Container;
use App\Core\CoreException;
use App\Core\MySql\MySql;
use App\Libs\Tools;
use App\Models\User\Exception\UserCannotBeCreatedException;
use App\Models\User\Exception\UserNotFoundException;

/**
 * User
 * Model controller for table `entity_users`
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */
class User
{
    private int $id;
    private string $uuid;
    private string $name;
    private string $email;
    private string $password;
    private string $registrationDate;
    private string $lastLoginDate;
    private bool $verified;
    private bool $status;
    private string $aliasTable = 'users';

    /**
     * @throws UserNotFoundException
     */
    public function __construct(int $id = null) {
        if ($id != null) {
            // Instance of Sql class
            $oMySql = new MySql();

            // $where Variable for where clause in the query
            $where = [
                'id = ?' => [
                    'type' => 'int',
                    'value' => $id,
                ]
            ];

            // Query in database
            $response = $oMySql->select()->from($this->aliasTable)->where($where)->execute();
            if (!empty($response['data'])) {
                $data = $response['data'];

                // Set properties of model
                $this->id = $data['id'];
                $this->uuid = $data['uuid'];
                $this->name = $data['name'];
                $this->email = $data['email'];
                $this->password = $data['password'];
                $this->registrationDate = $data['registrationDate'];
                $this->lastLoginDate = $data['lastLoginDate'] ?? '';
                $this->verified = $data['verified'];
                $this->status = $data['status'];
            } else {
                // If user not found, throw an exception
                throw new UserNotFoundException('User not found.');
            }
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return  $this;
    }

    /**
     * @return string
     */
    public function getRegistrationDate(): string
    {
        return $this->registrationDate;
    }

    /**
     * @param string $registrationDate
     * @return $this
     */
    public function setRegistrationDate(string $registrationDate): User
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastLoginDate(): string
    {
        return $this->lastLoginDate;
    }

    /**
     * @param string $lastLoginDate
     * @return $this
     */
    public function setLastLoginDate(string $lastLoginDate): User
    {
        $this->lastLoginDate = $lastLoginDate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified;
    }

    /**
     * @param bool $verified
     * @return User
     */
    public function setVerified(bool $verified): User
    {
        $this->verified = $verified;
        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status): User
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Find id user by email
     *
     * @param string $email
     * @return User
     * @throws CoreException
     * @throws UserNotFoundException
     */
    public function findByEmail(string $email): User
    {
        // Object to access database
        $oMySql = new MySql();

        $where = [
            'email = ?' => [
                'type' => 'string',
                'value' => $email
            ],
        ];

        // Query in data base
        $response = $oMySql->select('id')->from($this->aliasTable)->where($where)->execute();

        // Get id and return an object instance
        if (!empty($response['data'])) {
            $userData = $response['data'];
            return new User($userData['id']);
        } else {
            throw new UserNotFoundException('User not found.');
        }
    }

    /**
     * Create a new record in database
     *
     * @throws UserCannotBeCreatedException
     * @throws CoreException
     */
    public function create(User $user): User
    {
        if (
            $user->name != null &&
            $user->email!= null &&
            $user->password!= null
        ) {
            // Instance of Sql class
            $oMySql = new MySql();

            try {
                $response = $oMySql->custom("
                INSERT INTO
                    `" . Container::getTable($this->aliasTable) . "`
                (
                    `uuid`,
                    `name`,
                    `email`,
                    `password`,
                    `registrationDate`
                )
                VALUES
                (
                    '" . Tools::scStr(Tools::getUUID()) . "',
                    '" . Tools::scStr($user->name) . "',
                    '" . Tools::scStr($user->email) . "',
                    '" . Tools::scStr($user->password) . "',
                    '" . Tools::scStr(Tools::date()) . "'
                );
            ")->execute(returnLastId: true);
            } catch (CoreException) {
                throw new UserCannotBeCreatedException('User cannot be created.');
            }
        } else {
            throw new UserCannotBeCreatedException('User cannot be created.');
        }

        return new User($response['lastInsert']);
    }

}