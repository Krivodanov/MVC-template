<?php
namespace MVC\App;

use MVC\App\Config;
/**
 * Класс содержит методы для отправки ответа
 */
class Response {
    public static $twig;
    /**
    * Метод использует шаблонизатор Twig для отправки ответа в виде HTML
    * @param $template Имя файла шаблона
    * @param $arrayData Ассациативный массив с данными для шаблона
    */
    public static function view($template, $arrayData){
        echo self::$twig->render($template, $arrayData);
    }
    /**
     * Метод отправляет заголовок с кодом состояния REDIRECT (302) на в параметре указанный маршрут
     * @param $route Строка содержащая маршрут перенаправления 
     * 
     */
    public static function redirect($route){
        header("Location: ".$route);
    }

    /**
    * Метод отправляет ответ в виде JSON из полученного массива
    * @param $arrayData Ассациативный массив с данными для ответа
    */
    public static function json($arrayData) {
        header('Access-Control-Allow-Headers: Content-Type');        
        header('Content-Type: application/json');
        echo json_encode($arrayData);
    }
    /**
     * Метод отправляет заголовоки
     * 
     * Access-Control-Allow-Origin
     * 
     * Access-Control-Allow-Credentials
     * 
     * Content-Security-Policy
     */
    public static function sendHeaders() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        //CSP блокируем загрузку скриптов и стилей с других ресурсов
        header('Content-Security-Policy: default-src self http://'.$_SERVER['HTTP_HOST'].';'
                . ' script-src self http://'.$_SERVER['HTTP_HOST'].';'
                . ' script-src-elem self http://'.$_SERVER['HTTP_HOST'].';'
                . ' style-src self http://'.$_SERVER['HTTP_HOST'].';'
                . ' img-src self http://'.$_SERVER['HTTP_HOST'].';');
    }

    public static function sessionStart() {
        session_start(['cookie_lifetime' => Config::SESS_TIME]);
        if (empty($_SESSION['sessid'])) {
            $_SESSION['sessid']= md5($_SERVER['REMOTE_ADDR'].time()); //Генерация sessid для авторизации
        }
    }

}
