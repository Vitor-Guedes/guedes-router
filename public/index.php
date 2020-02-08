<?php

include "../vendor/autoload.php";

$app = new Src\App();

$app->get('/', function () {
    echo 'home';
});

$app->get('/{id}', 'Index:index')
    ->setNamespace('App\\Controller');

$app->group('/user', function ($app) {
    $app->get('/login', function () {
        echo "Logar";
    });

    $app->group('/dash', function ($app) {
        $app->post('/load', 'App\\Controller\\Index');
        $app->get('/log', 'App\\Controller\\Index');

        $app->group('/coisas', function ($app) {
            $app->get('/{nomes}', function ($args) {
                var_dump($args);
                echo 'coisas nomes';
            });
        });

    });

    $app->post('/teste', function () {
        echo 'teste';
    });
});

$method = strtolower($_SERVER['REQUEST_METHOD']);
$uri = $_SERVER['REQUEST_URI'];

$app->dispatch($method, $uri);
