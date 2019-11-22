<?php
namespace MVC\App\Controllers;
use MVC\App\Models\User as Model;

/**
 * Контроллер User содержит методы обработки данных пользователя
 */

class User {
    /**
     * Метод ассациативный массив с данными пользователя
     * @return array Ассациативный массив с данными пользователя
     */
    public static function get() {
        $User = new Model();
        $result = $User->select()->where('sessid', '=', $_SESSION['sessid'])->Execute();
        return $result[0];
    }
}
