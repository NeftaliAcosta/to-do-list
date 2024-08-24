<?php 

namespace App\Core;

use Josantonius\Session\Session;
use App\Core\SystemException;

/**
 * Session  
 *
 * Librería que se utiliza para controlar las sesiones dentro del sistema
 *
 * @author Neftalí Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2021, NEFTALI ACOSTA
 * @link https://gubynetwork.com
 * @version 1.0
 */

class SystemSession{
    
    private string $prefix = 'job_';
    public object $session;

    public function __construct()
    {
        $this->session = new Session();
        $this->session::setPrefix($this->prefix);
        $this->session::init(10800);

    }

    /**
     * Setea una clave de sesion en el sistema
     *
     * @param array $claves
     * @example ['pais' => 'mx]
     * @return object
     */
    public function set(array $claves): object
    {
		$this->session::set($claves);
        return $this;
    }

    /**
     * Obtiene una clave de sesion en específica
     *
     * @param string $nombre Nombre de la clave
     * @return string
     */
    public function get(string $nombre): string
    {
        if (!empty(Session::get($nombre))){
            return Session::get($nombre);
        } else {
            return "";
        }
    }

    /**
     * Obtiene un array con todas las clave seteadas
     * Todas las claves tienen el prefijo definido. Por ejemplo pref_
     * @return array
     */
    public function getSessionData(): array
    {
        if (!empty(Session::get())) {
            return Session::get();
        } else {
            return [];
        }
    }

    /**
     * Obtiene el ID  de sesión único
     *
     * @return string
     */
    public function getSessionId(): string
    {
        return Session::id();
    }

    /**
     * Regenera el ID de de sesión actual
     *
     * @return object
     */
    public function regenerate(): object
    {
        $this->session::regenerate();
        return $this;
    }

    /**
     * Destruye una clave en específico o si no se define la clave se destruye toda la sesión
     *
     * @param string $key Clave a destruir
     * @return void
     */
    public function destroy(string $key = NULL): object
    {
        if($key===NULL){
            $this->session::destroy();
        }else{
            $this->session::destroy($key);
        }

        return $this;
    }


}