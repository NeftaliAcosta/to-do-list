<?php

namespace App\Core;

use Josantonius\Session\Exceptions\HeadersSentException;
use Josantonius\Session\Exceptions\SessionNotStartedException;
use Josantonius\Session\Exceptions\SessionStartedException;
use Josantonius\Session\Exceptions\WrongSessionOptionException;
use Josantonius\Session\Session;

/**
 * SystemSession
 * Controller that manages system sessions
 *
 * @author Neftalí Marciano Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2024, Neftali Marciano Acosta
 * @link https://www.linkedin.com/in/neftaliacosta
 * @version 1.0
 */

class SystemSession{

    /**
     * Session prefix
     * @var string
     */
    private string $prefix = 'todolist_';

    /**
     * Instance of session controller
     * @var Session
     */
    public Session $session;

    /**
     * @throws HeadersSentException
     * @throws WrongSessionOptionException
     * @throws SessionStartedException
     */
    public function __construct()
    {
        $this->session = new Session();
        $this->session->start([
            'cache_expire' => 180,
            'name' => $this->prefix
        ]);

    }

    /**
     * Set new session value
     *
     * @param string $name Name of session key
     * @param mixed $value Value of session key
     * @return SystemSession
     * @throws SessionNotStartedException
     */
    public function set(string $name, mixed $value): SystemSession
    {
        $this->session->set($name, $value);

        return $this;
    }

    /**
     * Get session value
     *
     * @param string $name Name of session key
     * @return string
     * @throws CoreException
     */
    public function get(string $name): string
    {
        if (!empty($this->session->get($name))){
            return $this->session->get($name);
        } else {
            throw new CoreException("Session key does not exist");
        }
    }

    /**
     * Get all values from the current session
     *
     * @return array
     */
    public function getSessionData(): array
    {
        return $this->session->all();
    }

    /**
     * Gets the unique session ID
     *
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->session->getId();
    }

    /**
     * Regenerates the current session Id
     *
     * @return SystemSession
     * @throws SessionNotStartedException
     */
    public function regenerate(): SystemSession
    {
        $this->session->regenerateId();

        return $this;
    }

    /**
     * Destroys the session
     *
     * @return SystemSession
     * @throws SessionNotStartedException
     */
    public function destroy(): SystemSession
    {
        $this->session->destroy();

        return $this;
    }

}