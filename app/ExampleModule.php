<?php

use Apitin\Database;
use Apitin\Module;
use Apitin\Router;

class ExampleModule extends Module
{
    /**
     * Build routes for module
     */
    public function route(Router $router): void
    {
        // Route / to: $this->test(...)
        $router->get('*', function($uri) {
            return $this->call('test', [
                'uri' => $uri,
            ]);
        });
    }

    /**
     * Callback handler for a route
     * 
     * Database implements DI class
     * -> $db will be automatically populated with Database::factory()
     */
    public function test($uri, Database $db)
    {
        return [
            'uri' => $uri,
            'foo' => $db->one('SELECT CURRENT_TIMESTAMP()'),
            'bar' => config('DATABASE_HOSTNAME', ''),
        ];
    }
}