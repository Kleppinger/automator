<?php

namespace Automator\Application\Controller;

use Automator\Application\Controller\Controller;
use Klein\Klein;

class ErrorController extends Controller
{

    public function error() {
        $this->view("Error.latte");
    }

    public function registerRoute(Klein $klein): void
    {
        $klein->onHttpError(function ($code, $router) {
            if ($code == 404) {
                # $router->response()->sendHeaders( true, true);
                $this->error();
                die;
            }
        });
    }
}