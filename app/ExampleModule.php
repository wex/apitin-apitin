<?php

use Apitin\Module;
use Apitin\Route;

class ExampleModule extends Module
{
    #[Route("/", ["GET"])]
    public function help()
    {
        $thisModule = get_class($this);
        $thisMethod = __FUNCTION__;
        $thisFile   = realpath(__FILE__);
        $configPath = realpath(APP_PATH . '/.env');

        $phpVersion = PHP_VERSION;
        $osVersion  = PHP_OS_FAMILY;
        $phpModules = implode("\n", array_map(function($t) { return "<kbd>{$t}</kbd>"; }, get_loaded_extensions()));
        $wsVersion  = $_SERVER['SERVER_SOFTWARE'] ?? '[unknown]';

        try {
            if (($version = Apitin\Database::factory()->one('SELECT VERSION()'))) {
                $dbOk = '<span class="ui green basic label">' . $version . '</span>';
            } else {
                $dbOk = '<span class="ui red basic label">FAIL</span>';
            }
        } catch (Exception $e) {
            $dbOk = '<span class="ui red basic label">FAIL</span>';
        }

        echo <<<EOF
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
            <div class="ui divider"></div>
            <div class="ui padded basic segment">
                <h2 class="ui header">
                    Environment
                </h2>
                <table class="ui definition table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Version</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>OS</td>
                            <td>{$osVersion}</td>
                        </tr>
                        <tr>
                            <td>PHP</td>
                            <td>{$phpVersion}</td>
                        </tr>
                        <tr>
                            <td>Modules</td>
                            <td>{$phpModules}</td>
                        </tr>
                        <tr>
                            <td>Server</td>
                            <td>{$wsVersion}</td>
                        </tr>
                        <tr>
                            <td>Database</td>
                            <td>{$dbOk}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </body>
</html>
EOF;
        exit;
    }
}