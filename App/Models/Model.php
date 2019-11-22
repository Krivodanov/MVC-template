<?php

namespace MVC\App\Models;

class Model {
    protected $tableName;
    protected $DB;
    protected $query;
    protected $queryTitle;
    protected $where;
    protected $condition;
    public function __construct() {
        $host = "127.0.0.1"; //Хост базы данных
        $database = "comments"; //Имя базы данных
        $dbuser = "root"; //Имя пользователя базы данных
        $dbpassword = ""; //Пароль доступа к базе данных
        $this->DB = mysqli_connect($host, $dbuser, $dbpassword, $database); //Подключение к базе данных
    }
    /**
    * Этот метод формирует начало SQL запроса типа:"SELECT * FROM <таблица_модели>" и возвращает объект Model
    * для вызова дополнительных методов (where(), Execute() и др.)
    * @param array $fields Массив наименований полей таблицы для выбора, без параметра делается выборка всех полей ['*']
    * @return Model
    */
    public  function select ($fields=['*']){
        $this->queryTitle = "SELECT ". implode(", ", $fields)." FROM ".$this->tableName;
        return $this;
    }
    /**
    * Этот метод добавляет одно условие "WHERE" к SQL запросам(select, update, delete) и возвращает объект Model
    * для вызова дополнительных методов (where(), Execute() и др.)
    * @param string $field Наименование поля
    * @param string $operator Оператор сравнения
    * @param string $value Значение
    * @return Model
    */
    public function where($field, $operator, $value) {
        $this->where[] = $field.$operator."'".$value."'";
        $this->condition = " WHERE ".implode(" AND ", $this->where)." ";
        return $this;
    }
    /**
    * Метод для отладки SQL запросов, взвращает строку запроса сформированную другими методами
    * @return string Cтрока запроса сформированная другими методами
    */
    public function getQuery() {
        $this->$query = $this->queryTitle.$this->condition;
        return $this->$query;
    }
    /**
    * Вызов метода выполняет SQL запрос, сформированный другими методами
    * @return boolean для запросов типа: INSERT, UPDATE, DELETE, возвращает TRUE в случае успеха и FALSE в случае ошибки
    * @return array для запросов типа: SELECT, ассациативный массив с данными полученными в результате запроса
    */
    public function execute() {
        $this->$query = $this->queryTitle.$this->condition;
        $result = $this->DB->query($this->$query);
        $this->queryTitle = '';
        $this->where = [];
        $this->condition = '';
        if (is_object($result)) {
            foreach ($result as $key => $value) {
                $result_array[$key]=$value;
            }
            /*foreach ($result as $key=>$value) {
                $result1[$key]= new class {};
                foreach ($value as $key1=>$value1) {
                    $result1[$key]->{$key1}=$value1;
                }
            }*/
            return $result_array;
        }
        return $result;
    }
    /**
    * Этот метод формирует SQL запрос INSERT и возвращает объект Model
    * для вызова исполняющего метода Execute()
    * @param array $dataArray Ассациативный массив наименований полей и их значений пример:['name'=>'Jon', 'age'=>22]
    * @return Model
    */
    public function insert ($dataArray){
        $rows = implode(", ", array_keys($dataArray));
        $values = "'".implode("', '", $dataArray)."'";
        $this->queryTitle = "INSERT INTO ".$this->tableName." (".$rows.") "
                . "VALUES (".$values.");";
        return $this;
    }
    /**
    * Этот метод формирует SQL запрос UPDATE и возвращает объект Model
    * для вызова исполняющего метода Execute()
    * @param array $dataArray Ассациативный массив наименований полей и их значений пример:['name'=>'Jon', 'age'=>22]
    * @return Model
    */
    public function update ($dataArray){
        foreach ($dataArray as $key => $value) $set[] = $key."='".$value."'";
        $values = implode(", ",  $set);
        $this->queryTitle = "UPDATE ".$this->tableName." SET ".$values;
        return $this;
    }
    /**
    * Этот метод формирует начало SQL запроса DELETE и возвращает объект Model
    * для вызова дополнительных методов (where(), Execute())
    * @return Model
    */
    public function delete() {
        $this->queryTitle = "DELETE FROM  ".$this->tableName;
        return $this;
    }
}