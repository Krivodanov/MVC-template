<?php


namespace MVC\App;


class Route
{
    protected static function condition ($condition=[true])
    {
        $result = true;
        foreach ($condition as $value){
            if ($value == false) $result = false;
        }
        return $result;
    }

    public static function get($route, $func, $condition=[true])
    {
        if (strtoupper(Request::method())=='GET' AND Request::route()==$route AND self::condition($condition)==true) {
            $func();
        }
        return true;
    }

    public static function post($route, $func, $condition=[true])
    {
        if (strtoupper(Request::method()) =='POST' AND Request::route()==$route  AND self::condition($condition)==true) {
            $func();
        }
        return true;
    }
}