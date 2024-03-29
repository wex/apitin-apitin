# Apitin - ultra lightweight API framework

* Requires `PHP 8.0+`
* Development can be done with `built-in web server`
* `Apache` & `mod_rewrite` supported
* Can be used with `modules` and pure `callbacks`
* Supports magical loading with `static dependency injection`

## TODO

* Route caching

## Getting started

1. Create new project using `composer` with:

```sh
composer create-project apitin/apitin example-api
```

2. Start internal web server
```sh
cd example-api
composer run dev
```

3. Visit `http://127.0.0.1:3000/` and start developing!

## Features

1. Custom router
```php
$router->get('foo/*', function($uri) {});
$router->get('bar/{id}', function($id) {});

$router->with('user', function() use ($router) {
    $router->get('view/{id}', function($id) {}); // user/view/{id}
    $router->get('edit/{id}', function($id) {}); // user/edit/{id}
});
```

2. Extended `PDO` library
```php
$db = Database::factory();

// Fetch multiple
foreach ($db->all('SELECT * FROM `test`') as $t) {}

// Fetch single
$entry = $db->first('SELECT * FROM `test` WHERE `id` = ?', 1);

// Fetch column
$sum = $db->one('SELECT SUM(`price`) FROM `test`');

// Insert
$db->insert('test', ['foo' => 'bar']);

// Update
$db->update('test', ['foo' => 'bar'], ['id' => 1]);

// Replace
$db->replace('test', ['foo' => 'bar'], ['id' => 1]);

// Delete
$db->delete('test', ['id' => 1]);
```

3. Static dependency injection
```php
class FooModule extends Apitin\Module 
{
    public function route(Router $router): void
    {
        $router->get('*', function($uri) {
            return $this->call('test');
        });
    }

    public function test(Apitin\Database $database)
    {
        
    }
}
```

4. Module registration event & call event
```php
class FooModule extends Apitin\Module 
{
    public function onRegister(Apitin\Application &$application): void
    {
        // Here be dragons.
    }

    public function onCall(Apitin\Application &$application): void
    {
        // Called while current module is called (route is matched)
    }
}
```

5. Alternative routing with `Attributes`
```php
class TestModule extends Apitin\Module
{
    #[Route("*", ["GET"])]
    public function test($uri)
    {
        echo "Called for: {$uri}";
        exit;
    }
}
```

6. Alternative static dependency injection with `Attributes`
```php
class TestModule extends Apitin\Module
{
    #[Inject(Apitin\Database::class)]
    protected Apitin\Database $db;

    #[Route("*", ["GET"])]
    public function test($uri)
    {
        var_dump( $this->db );
        exit;
    }
}
```

7. Http-helper
```php
print_r( Http::post('https://example.com/')->json() );
```

8. CLI-support
```sh
# This is mapped to GET /test
php index.php test
```

9. Templates with partials
```php
class TestModule extends Apitin\Module
{
    #[Route("/test", ["GET"])]
    public function test()
    {
        return Template::create(APP_PATH . 'views/test.phtml');
    }
}
```