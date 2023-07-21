# Router
Pacote para criar rotas, para aplicações com arquitetura MVC.

## Instanciação
É possivel mudar o separador que vai identificar a classe e funcão a ser executados nas rotas.
```php
use GuedesRouter\RouteCollection;

$collection = new RouterCollection();

// ou
$collection = new RouterCollection(':');
```

## Rota Basica
```php
use GuedesRouter\RouteCollection;

$collection = new RouterCollection();

$collection->get('/', function () {
    return "Hello World!";
});
```

## Verbos Http Disponiveis
```php
/** trait GuedesRouter/Traits/Verbs */
$collection->get($uri, $callaback);
$collection->post($uri, $callaback);
$collection->put($uri, $callaback);
$collection->delete($uri, $callaback);
```

## Rotas com parametros
```php
$collection->get('/customer/{name}', function ($name) {
    return "Customer Id: $name";
});

$colection->post('/country/{country}/state/{state}', function ($country, $state) {
    return "Pais: $country, Estado: $state";
});
```

## Callaback das Rotas
```php
$collection = new RouterCollection(':');

/** closure */
$collection->get('/', function () {
    return "Hello World!";
});

/** string - Separador ':' definido na criação da $collection por default o separador é o '@' */
$collction->post('/store', 'Namespace/Controllers/IndexController:index');

/** array */
$collection->put('/customer/{id}', [CustomerController:class, 'update']);
```

## Execução da rota
```php
$route = $collection->get('/', function () {
    return "Hello World!";
})

$response = $route->resolve();
```

## Identificação da rota
```php
$collection->get('/', function () {
    return "Página html renderezida.";
})
$collection->get('/contact', function () {
    return "Página html renderezida.";
})
$collection->get('/about', function () {
    return "Página html renderezida.";
})

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$matchedRoute = null;
foreach ($this->routerCollection->getRoutes($method) as $route) {
    if ($route->match($uri)) {
        $matchedRoute = $route;
        break ;
    }
}

if ($matchedRoute) {
    echo $matchedRoute->resolve();
}
```