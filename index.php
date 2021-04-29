<?php
    /**
     * Apitin project template
     * 
     * @copyright Niko Hujanen, 2021
     * @author Niko Hujanen <niko.hujanen@gmail.com>
     */

    define('VENDOR_PATH',   __DIR__ . '/vendor/');
    define('PUBLIC_PATH',   rtrim(__DIR__, DIRECTORY_SEPARATOR));
    define('APP_PATH',      PUBLIC_PATH . '/app/');

    require_once VENDOR_PATH . 'autoload.php';

    /**
     * You can send custom headers here.
     * 
     * header('Access-Control-Allow-Origin: *');
     */

    /**
     * Initialize application and register modules.
     */
    $app = new Apitin\Application;
    $app->register(
        ExampleModule::class
    );

    /**
     * Handle request and response.
     */
    try {

        $result = $app();

        header("Content-Type: application/json");
        http_response_code(200);
        echo json_encode($result);
        exit(0);
        
    } catch (Throwable $e) {

        if (is_subclass_of($e, Apitin\Router\RouterException::class)) {

            http_response_code($e->getCode());
            dprint_r($e);
            exit(1);

        } else {

            log_r($e);
            http_response_code(500);
            dprint_r($e);
            exit(1);

        }

    }