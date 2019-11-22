<?php
namespace MVC\App\Controllers;
use MVC\App\Models\User;
/**
 * Класс содержит методы реалицации авторизации на сайте.
 * 
 * Метод авторизации Login
 * 
 * Метод регистрации Registration
 * 
 * Метод проверки активности сессии Active
 * 
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
        $User = new User();
        $result = $User->select()
                    ->where('email', '=', $request->email)
                    ->where('password', '=', md5($request->password))
                    ->Execute();
        if (gettype($result)=='array') {
            $User->update(['sessid'=>$_SESSION['sessid']])
                ->where('email', '=', $request->email)
                ->where('password', '=', md5($request->password))
                ->Execute();
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
        $User = new User();
        $result = $User->select()->where('sessid', '=', $_SESSION['sessid'])->Execute();
        if ($result==null) {
            $User->insert([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>md5($request->password),
                'sessid'=>$_SESSION['sessid']])->execute();
            return true;
        } else {
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
        $User = new User();
        $User->update(['sessid'=>''])->where('sessid', '=', $sessid)->Execute();
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
        $User = new User();
        if (gettype($User->select()->where('sessid', '=', $sessid)->Execute())=='array') return true;
        return false;
    }
}
