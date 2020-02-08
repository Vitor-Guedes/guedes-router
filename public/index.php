<?php

include "../vendor/autoload.php";

$app = new Src\App();

$app->get('/', function () {
    echo 'home';
});

$appController = 'App\\Controller\\Admin';
$app->get('/admin', 'Admin:logout')
    ->setNamespace($appController);
$app->group('/admin', function ($app) {
    $app->get('/login', 'Admin:login');

    $app->get('/logout', 'Admin:logout');
})->setNamespace($appController);

$app->group('/user', function ($app) {
    $app->get('/index', 'User:index');
})->setNamespace('App\\Controller\\User');

$method = strtolower($_SERVER['REQUEST_METHOD']);
$uri = $_SERVER['REQUEST_URI'];

$app->dispatch($method, $uri);
