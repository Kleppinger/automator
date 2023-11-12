<?php

namespace Automator\Application\Controller;

use Automator\Application\Controller\Controller;
use Klein\Klein;

class DashboardController extends Controller
{

    public function home() {
        $currentSession = SessionController::$instance->getCurrentSession();
        if(!$currentSession->getUser()) {
            header("Location: /login");
            die;
        }
        $this->view("Home.latte");
    }
    public function registerRoute(Klein $klein): void
    {
        $klein->get("/", function() {
           $this->home();
        });
    }
}