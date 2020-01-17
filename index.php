<?php
require __DIR__.'/vendor/autoload.php';

use MVC\App\Libraries;
use MVC\App\Request;
use MVC\App\Response;
use MVC\App\Controllers\User;
use MVC\App\Controllers\Auth;
use MVC\App\Route;

//Инициализация библиотек
new Libraries();
//Отправка HTTP заголовков
Response::sendHeaders();
//Инициализация сессии
Response::sessionStart();

Route::get('test', function (){
   \MVC\App\Controllers\Migration::all();
});

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
    if (Auth::registration(Request::all())){
        Response::redirect('../home');
    } else {
        Response::view('registration.html.twig', [
            'title'=>'Регистрация',
            'error'=>'Пользователь с таким email существует']);
    }
});
//Завершение сессии
Route::get('auth/logout', function (){
    Auth::logout();
    Response::redirect('../');
});
//Домашняя страница
Route::get('home', function (){
    $user = User::get();
    Response::view('home.html.twig', [
        'title'=>'Личный кабинет',
        'user'=>$user]);
}, [Auth::active()]);
//Переадресация если сессия не активна
Route::get('home', function (){
    Response::redirect('auth/login');
}, [!Auth::active()]);

