<?php

namespace Automator\Application\Controller;

use Automator\Application\Controller\Controller;
use Klein\Klein;

class SettingsController extends Controller
{

    public function render() {
        $this->view("Settings.latte");
    }
    public function registerRoute(Klein $klein): void
    {
        $klein->get("/settings", function() {
            $this->render();
        });
    }
}