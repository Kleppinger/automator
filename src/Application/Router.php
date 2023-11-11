<?php

namespace Automator\Application;

use Automator\Application\Controller\Controller;
use Klein\Klein;

class Router
{

    public ?Klein $klein;
    private ?Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->klein = new Klein();
        $klein = $this->klein;
        $controllers = $this->application->controllerCollection->toArray();
        foreach($controllers as $controller) {
            $controller->registerRoute($klein);
        }
    }

    public function run() {
        $this->klein->dispatch();
    }

}