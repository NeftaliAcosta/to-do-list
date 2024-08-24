<?php 

namespace App\Controllers;

use App\Core\SystemException;

/**
 * Container 
 *
 * Clase que contiene las instancias de los modelos que hacen consulta a la base de datos
 * Esta clase se utiliza en las peticiones que realizan los controladores hacia el modelo
 * También almacena el nombre de las tablas utilizadas en la bases de datos así, cuando se
 * realice un cambio se modifica este controlador y no todo el código en el sistema
 *
 * @author Neftalí Acosta <neftaliacosta@outlook.com>
 * @copyright (c) 2021, NEFTALI ACOSTA
 * @link https://gubynetwork.com
 * @version 1.0
 */

class Container{

	/**
     * @var array $tablas Nombre con el que se accede a la table de la base de datos
     */
	public array $tablas = array(
		'alias_usuarios' => 'table_usuarios',

	);

    /**
     * Obtiene el nomobre real de la tabla en la base da datos
     *
     * @param string $tabla
     * @return string
     */
	public function getTabla(string $tabla): string
	{
		try {
			if (array_key_exists($tabla, $this->tablas)) {
				return $this->tablas[$tabla];
			}else{
				throw new SystemException('El index de la tabla seleccionada no existe.');
			}
		} catch (SystemException $e) {
			echo $e->errorMessage();
		}
	}
}
