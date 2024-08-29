<?php

namespace App\Models\Task;

use App\Controllers\ToolsModels;
use App\Core\Container\Container;
use App\Core\CoreException;
use App\Core\MySql\MySql;
use App\Libs\Tools;
use App\Models\Task\Exception\TaskCannotBeCreatedException;
use App\Models\Task\Exception\TaskCannotBeUpdatedException;
use App\Models\Task\Exception\TaskNotFoundException;
use App\Models\User\User;
use Exception;

/**
 * Task
 * Model controller for table `entity_tasks`
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */
class Task extends ToolsModels
{
    private int $id;
    private string $uuid;
    private string $title;
    private string $description;
    private int $status;
    private string $creationDate;
    private int $user_id;
    private string $aliasTable = 'tasks';

    /**
     * @throws TaskNotFoundException
     */
    public function __construct(int $id = null) {
        parent::__construct($this->aliasTable);

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
                $this->title = $data['title'];
                $this->description = $data['description'];
                $this->status = $data['status'];
                $this->creationDate = $data['creationDate'];
                $this->user_id = $data['user_id'] ?? '';
            } else {
                // If user not found, throw an exception
                throw new TaskNotFoundException('Task not found.');
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Task
     */
    public function setTitle(string $title): Task
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): Task
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): Task
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    /**
     * @param string $creationDate
     * @return $this
     */
    public function setCreationDate(string $creationDate): Task
    {
        $this->creationDate = $creationDate;
        return $this;
        /**
         *
         */
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return $this
     */
    public function setUserId(int $user_id): Task
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Get all rows
     *
     * @param User $user
     * @return array
     */
    public function getAll(User $user): array
    {
        // Instance of Sql class
        $oMySql = new MySql();

        // $where Variable for where clause in the query
        $where = [
            'user_id = ?' => [
                'type' => 'int',
                'value' => $user->getId(),
            ]
        ];

        // Query in database
        $response = $oMySql->select()->from($this->aliasTable)->where($where)->fetchAll()->execute();

        return $response['data'];
    }

    /**
     * Create a new record in database
     *
     * @param Task $task
     * @return Task
     * @throws TaskCannotBeCreatedException
     * @throws TaskNotFoundException
     */
    public function create(Task $task): Task
    {
        if (
            $task->title != null &
            $task->description != null &
            $task->status != null &&
            $task->user_id != null
        ) {
            // Instance of Sql class
            $oMySql = new MySql();

            try {
                $response = $oMySql->custom("
                INSERT INTO
                    `" . Container::getTable($this->aliasTable) . "`
                (
                    `uuid`,
                    `title`,
                    `description`,
                    `status`,
                    `creationDate`,
                    `user_id`
                )
                VALUES
                (
                    '" . Tools::scStr(Tools::getUUID()) . "',
                    '" . Tools::scStr($task->title) . "',
                    '" . Tools::scStr($task->description) . "',
                    " . Tools::scInt($task->status) . ",
                    '" . Tools::scStr(Tools::date()) . "',
                    " . Tools::scInt($task->user_id). "
                );
            ")->execute(returnLastId: true);
            } catch (CoreException) {
                throw new TaskCannotBeCreatedException('Task cannot be created.');
            }
        } else {
            throw new TaskCannotBeCreatedException('Task cannot be created');
        }

        return new Task($response['lastInsert']);
    }

    /**
     * Update record in database
     *
     * @throws TaskCannotBeUpdatedException
     */
    public function update(Task $task): Task {
        if (
            $task->title != null &
            $task->description != null &
            $task->status != null &&
            $task->user_id != null
        ) {
            // Instance of Sql class
            $oMySql = new MySql();

            // $where Variable for where clause in the query
            $where = [
                'id = ?' => [
                    'type' => 'int',
                    'value' => $task->getId(),
                ]
            ];

            try {
                $oMySql->update()->updateString('title', $task->title)->from($this->aliasTable)->where($where)->execute();
                $oMySql->update()->updateString('description', $task->description)->from($this->aliasTable)->where($where)->execute();
                $oMySql->update()->updateInt('status', $task->status)->from($this->aliasTable)->where($where)->execute();
            }catch ( Exception $e){
                throw new TaskCannotBeUpdatedException('Task cannot be updated.');
            }
        } else {
            throw new TaskCannotBeUpdatedException('Task cannot be updated.');
        }

        return $task;
    }
}