<?php

use Apitin\Module;
use Apitin\Route;

class ExampleModule extends Module
{
    #[Route("/test", ["GET"])]
    public function test()
    {
        return [
            '_comment'  => 'This is a JSON response.',
            'foo'       => 'bar',
            'now'       => date('c'),
        ];
    }

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

        return Apitin\Template\Template::create(
            APP_PATH . '/views/help.phtml',
            [
                'thisModule'    => $thisModule,
                'thisMethod'    => $thisMethod,
                'thisFile'      => $thisFile,
                'configPath'    => $configPath,
                'phpVersion'    => $phpVersion,
                'osVersion'     => $osVersion,
                'phpModules'    => $phpModules,
                'wsVersion'     => $wsVersion,
                'dbOk'          => $dbOk,
            ]
        );
    }
}