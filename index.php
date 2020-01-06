<?php
require __DIR__.'/vendor/autoload.php';

use MVC\App\Request;
use MVC\App\Response;
use MVC\App\Controllers\User;
use MVC\App\Controllers\Auth;
use MVC\App\Route;

Response::sendHeaders();

session_start(['cookie_lifetime' => 6048000]); //Время хранения сессии 70 дней
if (empty($_SESSION['sessid'])) {
    $_SESSION['sessid']= md5($_SERVER['REMOTE_ADDR'].time()); //Генерация sessid для авторизации
}

//Стартовая страница
Route::get('', function (){
    Response::view('index.html.twig', ['title'=>'Стартовая страница']);
});
//Страница о приложении
Route::get('about', function (){
    Response::view('about.html.twig', ['title'=>'О приложении']);
});
//Переадресация если сессия активна
Route::get('auth/login', function (){
    Response::redirect('../home');
}, [Auth::active()]);
//Форма авторизации пользователя
Route::get('auth/login', function (){
    Response::view('login.html.twig', ['title'=>'Авторизация']);
}, [!Auth::active()]);
//Авторизация пользователя
Route::post('auth/login', function (){
    Auth::login(Request::all());
    Response::redirect('../home');
});
//Переадресация если сессия активна
Route::get('auth/registration', function (){
    Response::redirect('../home');
}, [Auth::active()]);
//Форма регистрации пользователя
Route::get('auth/registration', function (){
    Response::view('registration.html.twig', ['title'=>'Регистрация']);
}, [!Auth::active()]);
//Регистрация пользователя
Route::post('auth/registration', function (){
    Auth::registration(Request::all());
    Response::redirect('../home');
});
//Завершение сессии
Route::get('auth/logout', function (){
    Auth::logout();
    Response::redirect('../');
});
//Домашняя страница
Route::get('home', function (){
    Response::view('home.html.twig', [
        'title'=>'Личный кабинет',
        'name'=>User::get()['name'],
        'email'=>User::get()['email'],
        'date'=>User::get()['create_date']]);
}, [Auth::active()]);
//Переадресация если сессия не активна
Route::get('home', function (){
    Response::redirect('auth/login');
}, [!Auth::active()]);

