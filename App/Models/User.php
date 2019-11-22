<?php

namespace MVC\App\Models;

/**
 * Модель User наследуется от родительской модели Model 
 * содержит методы обработки данных пользователя CRUD
 */
class User extends Model {
    /**
     * __construct задает свойство модели tableName = "users"
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = "users";
    }
    
}
