<?php
require __DIR__.'/vendor/autoload.php';

use MVC\App\Request;
use MVC\App\Response;
use MVC\App\Controllers\User;
use MVC\App\Controllers\Auth;

Response::sendHeaders();

session_start(['cookie_lifetime' => 6048000]); //Время хранения сессии 70 дней
if (empty($_SESSION['sessid'])) {
    $_SESSION['sessid']= md5($_SERVER['REMOTE_ADDR'].time()); //Генерация sessid для авторизации
}

//Маршутизация запросов
switch (Request::route()) {
    //Стартовая страница
    case '': 
        Response::view('index.html.twig', ['title'=>'Стартовая страница']);
        break;
    //Страница о приложении
    case 'about': 
        Response::view('about.html.twig', ['title'=>'О приложении']);
        break;
    //Авторизация пользователя
    case 'auth/login':
        if (Auth::active($_SESSION['sessid'])) Response::redirect('../home'); 
        if (Request::method()=='GET') Response::view('login.html.twig', ['title'=>'Авторизация']);
        if (Request::method()=='POST') {
            Auth::login(Request::all());
            Response::redirect('../home');
        }
        break;
    //Регистрация пользователя    
    case 'auth/registration':
        if (Auth::active($_SESSION['sessid'])) Response::redirect('../home'); 
        if (Request::method()=='GET') Response::view('registration.html.twig', ['title'=>'Регистрация']);
        if (Request::method()=='POST') {
            Auth::registration(Request::all());
            Response::redirect('../home');      
        }
        break;
    //Обнуление авторизации пользователя    
    case 'auth/logout':
        Auth::logout();
        Response::redirect('../');
        break;    
    //Личный кабинет пользователя    
    case 'home':
        if (Auth::active()) Response::view('home.html.twig', [
            'title'=>'Личный кабинет',
            'name'=>User::get()['name'],
            'email'=>User::get()['email'],
            'date'=>User::get()['create_date']]);
        if (!Auth::active()) Response::redirect('auth/login');
        break; 
}

