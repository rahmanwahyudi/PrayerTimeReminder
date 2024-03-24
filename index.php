<?php

session_start();

require_once __DIR__.'/vendor/autoload.php';

use Controllers\BoxController;
use Controllers\PrayerController;
use Controllers\SubscriberController;
use Utils\Env;
use Utils\Router;

Env::load();

$router = new Router();

$router->register('/', function () {
    $controller = new PrayerController();
    $controller->index();
});

$router->register('/subscriber', function () {
    $controller = new SubscriberController();
    $controller->index();
});

$router->register('/login', function () {
    $controller = new SubscriberController();
    $controller->login();
});

$router->register('/signout', function () {
    $controller = new SubscriberController();
    $controller->signOut();
});

$router->register('/create', function () {
    $controller = new BoxController();
    $controller->create();
});

$router->register('/update-prayer-time', function () {
    $controller = new PrayerController();
    $controller->updatePrayerTimes();
});

$router->resolve();
