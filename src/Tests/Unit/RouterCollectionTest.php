<?php

namespace GuedesRouter\Tests\Unit;

use PHPUnit\Framework\TestCase;
use GuedesRouter\{
    Route,
    RouterCollection
};

class RouterCollectionTest extends TestCase
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
}