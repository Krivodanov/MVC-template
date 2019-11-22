<?php
namespace MVC\App;
/**
* Класс содержит методы для получения данных о текущем запросе
*/
class Request {
    /**
     * Метод создает объект Request со свойствами равными полученным параметрам в запросе любым из методов. А так же свойство Headers с заголовками запроса
     * 
     * Метод применяет функцию htmlspecialchars($value) к каждому полученному параметру для избежания XSS-атак
     * @return Request
     */
    public static function all(){
        if (isset(Request::Headers()['Content-Type']) AND Request::Headers()['Content-Type']=='application/json'){
            $request = json_decode(file_get_contents("php://input"));
        } else {
            $request = $_REQUEST;
        }
        $result = new Request;
        foreach ($request as $key => $value) $result->{$key}=htmlspecialchars($value);
        $result->{'Headers'} = self::Headers();
        return $result;
    }
    /**
    * Метод оперделяет HTTP метод запроса.
    * @return string Строка 'название метода' которым был получен запрос ('GET', 'POST' и т.д.)
    */
    public static function method(){
        $method = $_SERVER['REQUEST_METHOD'];
        return $method;
    }
    /**
    * Метод оперделяет тип запроса.
    * @return string Строка 'тип запроса' которым был получен запрос ('HTML' или 'JSON')
    */
    public static function type(){
        if (Request::Headers()['Content-Type']=='application/json') return 'JSON';
        return 'HTML';
    }
    /**
    * Метод возвращает все заголовки для текущего HTTP-запроса.
    * @return array Ассоциативный массив, содержащий все HTTP-заголовки для данного запроса
    */
    public static function headers() {
        return getallheaders();
    }
    /**
    * Метод удаляет начальные и конечные "/" с URI запроса.
    * @return string Возвращает строку URI(маршрут)
    */
    public static function route() {
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        return trim($uri[0], '/');
    }
}