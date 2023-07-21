<?php

namespace GuedesRouter\Tests\Unit;

use PHPUnit\Framework\TestCase;
use GuedesRouter\{
    Route,
    RouterCollection
};

class RouterTest extends TestCase
{
    protected $routerCollection;

    protected function setUp() : void
    {
        $collection = new RouterCollection();

        // Routes
        $collection->get('/', 'GuedesRouter\Tests\Unit\RouterTest@fun_test_callback_string');
        $collection->post('/store', []);
        $collection->put('/update', []);
        $collection->get('/post/{post_id}', []);
        $collection->get('/get', []);
        $collection->delete('/customer/{customer_id}', []);
        $collection->put('/country/{country_code}/state/{state_code}', []);
        $collection->get('/status', function () {
            return 'ok';
        });
        $collection->post('/status', function () {
            return 'post-ok';
        });
        $collection->get('/customer/{customer_name}', function ($customerName) {
            return $customerName;
        });
        $collection->post('/project/{project_name}/tested-with/{status}', function ($projectName, $status) {
            return "Projeto: $projectName, testado com $status";
        });
        $collection->post('/second', [RouterTest::class, 'fun_test_callback_string']);

        $this->routerCollection = $collection;
    }

    /**
     * @param string $uri
     * @param string $method
     * 
     * @return \stdClass|Route
     */
    protected function findRoute(string $uri, string $method = 'get')
    {
        /** @var \stdClass|Route $matchedRoute */
        $matchedRoute = new \stdClass();
        foreach ($this->routerCollection->getRoutes($method) as $route) {
            if ($route->match($uri)) {
                $matchedRoute = $route;
                break ;
            }
        }
        return $matchedRoute;
    }

    public function test_must_be_able_to_identify_that_the_route_matches_the_requested_url_1()
    {
        $uri = '/';
        
        /** @var \stdClass|\GuedesRouter\Route $matchedRoute */
        $matchedRoute = $this->findRoute($uri);

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($uri, $matchedRoute->getPath());
    }

    public function test_must_be_able_to_identify_that_the_route_matches_the_requested_url_2()
    {
        $uri = '/store';
        $method = 'post';

        $matchedRoute = $this->findRoute($uri, $method);

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($uri, $matchedRoute->getPath());
    }

    public function test_should_not_find_the_requested_url_because_of_the_method()
    {
        $uri = '/update';
        $method = 'delete';
        
        $matchedRoute = $this->findRoute($uri, $method);

        $this->assertInstanceOf(\stdClass::class, $matchedRoute);
    }

    public function test_should_be_able_to_find_the_route_that_has_a_parameter()
    {
        $uri = '/post/2023';

        $matchedRoute = $this->findRoute($uri);

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals(
            str_replace('2023', '{post_id}', $uri),
            $matchedRoute->getPath()
        );
    }

    public function test_should_be_able_to_find_route_that_has_multiple_parameters()
    {
        $uri = '/country/BR/state/SP';
        $method = 'put';
        
        $matchedRoute = $this->findRoute($uri, $method);

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
        $uri = '/status';
        $expected = "ok";

        $matchedRoute = $this->findRoute($uri);

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($expected, $matchedRoute->resolve());
    }

    public function test_must_be_able_to_resolve_the_requested_url_with_parameters()
    {
        $uri = '/customer/Vitor-Guedes';
        $expected = "Vitor-Guedes";
        
        $matchedRoute = $this->findRoute($uri);

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($expected, $matchedRoute->resolve());
    }

    public function test_must_be_able_to_resolve_the_requested_url_with_mult_parameters()
    {
        $uri = '/project/Guedes-Router/tested-with/Sucesso';
        $expected = "Projeto: Guedes-Router, testado com Sucesso";
        
        $matchedRoute = $this->findRoute($uri, 'post'); 

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($expected, $matchedRoute->resolve());
    }

    public function test_must_be_able_to_resolve_the_callback_string()
    {
        $uri = '/';

        $matchedRoute = $this->findRoute($uri);

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($this->fun_test_callback_string(), $matchedRoute->resolve());
    }

    public function test_must_be_able_to_resolve_the_callback_array()
    {
        $uri = '/second';

        $matchedRoute = $this->findRoute($uri, 'post');

        $this->assertInstanceOf(Route::class, $matchedRoute);
        $this->assertEquals($this->fun_test_callback_string(), $matchedRoute->resolve());
    }

    /**
     * Function to simulate route with string ou array callback
     */
    public function fun_test_callback_string()
    {
        return __FUNCTION__;
    }
}