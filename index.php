<?php
    /**
     * Apitin project template
     * 
     * @copyright Niko Hujanen, 2021
     * @author Niko Hujanen <niko.hujanen@gmail.com>
     */

    define('STARTED_AT',    microtime(true));
    define('VENDOR_PATH',   __DIR__ . '/vendor/');
    define('PUBLIC_PATH',   rtrim(__DIR__, DIRECTORY_SEPARATOR));
    define('APP_PATH',      PUBLIC_PATH . '/app/');

    require_once VENDOR_PATH . 'autoload.php';

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

        if (is_subclass_of($result, Apitin\Renderable::class)) {

            $result->render();
            exit(0);

        } else {

            header("Content-Type: application/json");
            http_response_code(200);
            echo json_encode($result);
            exit(0);

        }

    } catch (Apitin\Router\ServeWithBuiltinException $e) {

        return false;
        
    } catch (Throwable $e) {

        if (Apitin\isDebugging()) {
            echo "<pre>{$e}</pre>";
        }

        if (is_subclass_of($e, Apitin\Router\RouterException::class)) {

            http_response_code($e->getCode());
            Apitin\dprint_r($e);
            exit(1);

        } else {

            Apitin\log_r($e);
            http_response_code(500);
            Apitin\dprint_r($e);
            exit(1);

        }

    }