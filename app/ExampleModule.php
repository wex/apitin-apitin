<?php

use Apitin\Module;
use Apitin\Router;

class ExampleModule extends Module
{
    public function route(Router $router): void
    {
        $router->get('*', function($uri) {
            echo $this->call('help');
            exit;
        });
    }

    public function help()
    {
        $thisModule = get_class($this);
        $thisMethod = __FUNCTION__;
        $thisFile   = realpath(__FILE__);
        $configPath = realpath(APP_PATH . '/.env');

        return <<<EOF
<!doctype html>
<html lang="en">
    <head>
        <title>Welcome to Apitin project</title>
        <style>kbd { display: inline-block; background: #333; color: #ccc; padding: 0.15em 0.25em; margin: 0.1em; border-radius: 3px; }</style>
        <script src="https://unpkg.com/jquery@3.3.1/dist/jquery.js"></script>
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/fomantic-ui@2.8.7/dist/semantic.min.css">
        <script src="https://unpkg.com/fomantic-ui@2.8.7/dist/semantic.min.js"></script
    </head>
    <body>
        <main class="ui container">
            <div class="ui padded basic segment">
                <h1 class="ui center aligned icon header">
                    <i class="circular flask icon"></i>
                    Welcome to Apitin!
                </h1>
            </div>
            <div class="ui divider"></div>
            <div class="ui padded basic segment">
                <h2 class="ui header">
                    General information
                </h2>
                <p>
                    You are currently viewing result of <kbd>{$thisModule}->{$thisMethod}()</kbd> at <kbd>{$thisFile}</kbd>.
                </p>
                <p>
                    You should add your database credentials to <kbd>{$configPath}</kbd>.
                </p>
            </div>
        </main>
    </body>
</html>
EOF;
    }
}