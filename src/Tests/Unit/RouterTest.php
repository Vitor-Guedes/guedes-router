<?php

namespace GuedesRouter\Tests\Unit;

use PHPUnit\Framework\TestCase;
use GuedesRouter\{
    Route,
    RouterCollection
};

class RouterTest extends TestCase
{
    public function test_must_identify_that_a_route_has_been_added_to_the_collection()
    {
        $routerCollection = new RouterCollection();
        
        $routerCollection->get('/', []);

        $this->assertEquals(1, count($routerCollection->getRoutes('get')));
    }

    public function test_should_identify_that_two_routes_were_added_to_the_collection()
    {
        $routerCollection = new RouterCollection();
        
        $routerCollection->get('/', []);

        $routerCollection->post('/store', []);

        $this->assertEquals(2, count($routerCollection->getRoutes()));
    }

    public function test_should_identify_that_three_routes_were_added_to_the_collection()
    {
        $routerCollection = new RouterCollection();
        
        $routerCollection->get('/', []);

        $routerCollection->post('/store', []);

        $routerCollection->put('/update', 'IndexController@index');

        $this->assertEquals(3, count($routerCollection->getRoutes()));
    }

    public function test_must_identify_the_return_is_an_instance_of_route()
    {
        $routerCollection = new RouterCollection();

        $route = $routerCollection->get('/', []);

        $this->assertInstanceOf(Route::class, $route);
    }

    public function test_must_be_able_to_identify_that_the_route_matches_the_requested_url_1()
    {
        $routerCollection = new RouterCollection();

        $uri = '/';
        $routerCollection->get($uri, []);

        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($routerCollection->getRoutes('get') as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($uri, $matchedRoute->getPath());
    }

    public function test_must_be_able_to_identify_that_the_route_matches_the_requested_url_2()
    {
        $routerCollection = new RouterCollection();

        $uri = '/store';

        $routerCollection->get('/', []);
        $routerCollection->post('/store', []);
        $routerCollection->put('/update', []);

        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($routerCollection->getRoutes('post') as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($uri, $matchedRoute->getPath());
    }

    public function test_should_not_find_the_requested_url_because_of_the_method()
    {
        $routerCollection = new RouterCollection();

        $uri = '/update';

        $routerCollection->get('/', []);
        $routerCollection->post('/store', []);
        $routerCollection->put('/update', []);

        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($routerCollection->getRoutes('delete') as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }

        $this->assertInstanceOf(\stdClass::class, $matchedRoute);
    }

    public function test_should_be_able_to_find_the_route_that_has_a_parameter()
    {
        $routerCollection = new RouterCollection();

        $uri = '/post/2023';

        $routerCollection->get('/', []);
        $routerCollection->post('/store', []);
        $routerCollection->get('/post/{post_id}', []);
        $routerCollection->put('/update', []);

        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($routerCollection->getRoutes('get') as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals(
            str_replace('2023', '{post_id}', $uri),
            $matchedRoute->getPath()
        );
    }

    public function test_should_be_able_to_find_route_that_has_multiple_parameters()
    {
        $routerCollection = new RouterCollection();

        $uri = '/country/BR/state/SP';

        $routerCollection->get('/', []);
        $routerCollection->post('/store', []);
        $routerCollection->get('/post/{post_id}', []);
        $routerCollection->get('/get', []);
        $routerCollection->delete('/{customer_id}', []);
        $routerCollection->put('/country/{country_code}/state/{state_code}', []);

        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($routerCollection->getRoutes('put') as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }

        $expected = str_replace('BR', '{country_code}', $uri);
        $expected = str_replace('SP', '{state_code}', $expected);
        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals(
            $expected,
            $matchedRoute->getPath()
        );
    }

    public function test_must_be_able_to_resolve_the_requested_url_simple_get_http()
    {
        $routerCollection = new RouterCollection();
        $uri = '/status';
        $expected = "ok";

        $routerCollection->get('/', []);
        $routerCollection->get('/status', function () use ($expected) {
            return $expected;
        });
        $routerCollection->post('/status', function () {
            return "post-ok";
        });

        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($routerCollection->getRoutes('get') as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }        

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($expected, $matchedRoute->resolve());
    }

    public function test_must_be_able_to_resolve_the_requested_url_with_parameters()
    {
        $routerCollection = new RouterCollection();
        $uri = '/customer/Vitor-Guedes';
        $expected = "Vitor-Guedes";

        $routerCollection->get('/', []);
        $routerCollection->get('/customer/{customer_name}', function ($customerName) {
            return $customerName;
        });
        $routerCollection->post('/status', function () {
            return "post-ok";
        });

        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($routerCollection->getRoutes('get') as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }        

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($expected, $matchedRoute->resolve());
    }

    public function test_must_be_able_to_resolve_the_requested_url_with_mult_parameters()
    {
        $routerCollection = new RouterCollection();
        $uri = '/project/Guedes-Router/tested-with/Sucesso';
        $expected = "Projeto: Guedes-Router, testado com Sucesso";

        $routerCollection->get('/', []);
        $routerCollection->get('/customer/{customer_name}', function ($customerName) {
            return $customerName;
        });
        $routerCollection->post('/status', function () {
            return "post-ok";
        });
        $routerCollection->post('/project/{project_name}/tested-with/{status}', function ($projectName, $status) {
            return "Projeto: $projectName, testado com $status";
        });

        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($routerCollection->getRoutes('post') as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }        

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($expected, $matchedRoute->resolve());
    }

    public function test_must_be_able_to_resolve_the_callback_string()
    {
        $routerCollection = new RouterCollection();
        $uri = '/';

        $routerCollection->get('/', 'GuedesRouter\Tests\Unit\RouterTest@fun_test_callback_string');

        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($routerCollection->getRoutes('get') as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }        

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($this->fun_test_callback_string(), $matchedRoute->resolve());
    }

    public function fun_test_callback_string()
    {
        return __FUNCTION__;
    }

    public function test_must_be_able_to_resolve_the_callback_array()
    {
        $routerCollection = new RouterCollection();
        $uri = '/second';

        $routerCollection->get('/second', [RouterTest::class, 'fun_test_callback_string']);

        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($routerCollection->getRoutes('get') as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }        

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($this->fun_test_callback_string(), $matchedRoute->resolve());
    }
}