<?php


namespace MVC\App;


class Config
{
    const SESS_TIME = 6048000;  //Время хранения сессии 70 дней

    const DB_DRIVER = 'mysql';
    const DB_HOST = 'localhost';
    const DB_DATABASE = 'db_mvc';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = '';
    const DB_CHARSET = 'utf8';
    const DB_COLLATION = 'utf8_unicode_ci';
    const DB_PREFIX = '';

}