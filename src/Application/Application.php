<?php

namespace Automator\Application;

use Automator\Application\Controller\ControllerCollection;
use Automator\Application\Model\Session;
use Automator\Application\Model\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\ToolsException;

class Application
{

    private static ?Application $instance;
    public ?Router $router;
    public ?\Adbar\Dot $config;
    public ?Database $database;
    public ?ControllerCollection $controllerCollection;

    /**
     * @throws Exception
     * @throws MissingMappingDriverImplementation
     * @throws ToolsException
     */
    public function __construct()
    {
        self::$instance = $this;
        $this->config = new \Adbar\Dot(require_once(__DIR__ . "/../../config/config.php"));
        $this->database = new Database($this);

        $this->controllerCollection = new ControllerCollection();
        $this->router = new Router($this);
    }

    private function loadControllers() {
        $namespace = 'Automator\Application\Controller';
        $baseClass = 'Automator\Application\Controller\Controller';
        $controllerFiles = array_diff(scandir(__DIR__ . "/Controller/"), array('.', '..'));
        foreach($controllerFiles as $file) {
            if(!str_ends_with($file, ".php")) continue;

            $className = str_replace(".php", "", $file);
            $fullClassName = $namespace . '\\' . $className;
            if(is_subclass_of($fullClassName, $baseClass)) {
                $this->controllerCollection->add(new $fullClassName());
            }
        }

    }

    public static function getInstance(): Application {
        return self::$instance;
    }

    public function config(string $dotNotation, mixed $default = null): mixed  {
        return $this->config->get($dotNotation, $default);
    }


    public function run() {
        $this->database->createUser();
        $this->loadControllers();
        $this->router->run();
    }

}