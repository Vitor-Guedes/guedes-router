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
        
    }

    // public function test_must_be_able_to_resolve_the_requested_url_simple_get_http()
    // {

    // }
}