<?php
use App\Http\Controllers\MainController;

if (!function_exists('count_notifications')) {
    function count_notifications($id)
    {
        $controller = new MainController();
        return $controller->countNotification($id);
    }
}


if (!function_exists('countalert')) {
    function countalert($id)
    {
        $controller = new MainController();
        return $controller->countalert($id);
    }
}