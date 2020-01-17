<?php
namespace MVC\App\Controllers;
use MVC\App\Models\User;
/**
 * Класс содержит методы реалицации авторизации на сайте.
 */
class Auth {
    /**
     * Метод Login реализует авторизацию, проверяет логин/пароль,
     * в случае удачной авторизации возвращает TRUE и обновляет sessid пользователя в табице,
     * в случае не верных данных FALSE. 
     * @param type $request Объект Request содержащий в свойствах параметры переданные в запросе,
     * а так же свойства заголовка запроса
     * @return boolean В случае успешной авторизации возвращает TRUE,
     * в случае не верных данных FALSE 
     */
    public static function login ($request) {
        $user = User::where('email', '=', $request->email)
            ->where('password', '=', md5($request->password))
            ->first();
        if ($user) {
            $user->sessid = $_SESSION['sessid'];
            $user->save();
            return true;
        } else {
            return false;
        }
    }
    /**
     * Метод Registration добавляет нового пользователя,
     * в случае удачной регистрации возвращает TRUE,
     * в случае ошибки возвращает FALSE. 
     * @param type $request Объект Request содержащий в свойствах параметры переданные в запросе,
     * а так же свойства заголовка запроса
     * @return boolean В случае успешной регистрации возвращает TRUE,
     * в случае ошибки FALSE 
     */    
    public static function registration ($request){
        $user = User::where('email', $request->email)->first();
        if ($user === null) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = md5($request->password);
            $user->sessid = $_SESSION['sessid'];
            $user->save();
            return true;
        } else {
            //Пользователь с таким email существует
            return false;
        }
    }
    /**
     * Метод Logout выберает пользователя из таблицы с переданным sessid
     * (Если параметр отсутствует используется $_SESSION['sessid'])
     * и обновляет запись пользователя устанавливая пустой sessid. 
     * @param string $sessid Идентификатор сесси сохраненный в $_SESSION['sessid'] или переданный в запросе
     * (При отсутствии параметра, принимает значение $_SESSION['sessid'])
     */ 
    public static function logout ($sessid='') {
        if ($sessid=='') $sessid=$_SESSION['sessid'];
        $user = User::where('sessid', $sessid)->first();
        $user->sessid = '';
        $user->save();
    }

    /**
     * Метод Active сравнивает $_SESSION['sessid'] пользователя с sessid хранящемся в таблице
     * если sessid имеются совпадения возвращает TRUE,
     * иначе возвращает FALSE. 
     * @param string $sessid Идентификатор сесси сохраненный в $_SESSION['sessid'] или переданный в запросе
     * (При отсутствии параметра, принимает значение $_SESSION['sessid'])
     * @return boolean Если сессия актуальна возвращает TRUE,
     * иначе возвращает FALSE 
     */     
    public static function active ($sessid='') {
        if ($sessid=='') $sessid=$_SESSION['sessid'];
        if ($sessid=='' OR $sessid==null) return false;
        $user = User::where('sessid', $sessid)->get();
        if ($user->count() === 1) return true;
        return false;
    }
}
