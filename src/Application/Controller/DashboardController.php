<?php

namespace Automator\Application\Controller;

use Automator\Application\Controller\Controller;
use Klein\Klein;

class DashboardController extends Controller
{

    public function home() {
        $this->view("Home.latte");
    }
    public function registerRoute(Klein $klein): void
    {
        $klein->get("/", function() {
           $this->home();
        });
    }
}