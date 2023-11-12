<?php

namespace Automator\Application\Controller;

use Automator\Application\Controller\Controller;
use Klein\Klein;

class FlowsController extends Controller
{

    public function registerRoute(Klein $klein): void
    {
        $klein->get("/flows", function($request) {
            $this->view("Flows.latte");
        });
        $klein->get("/flows/[:name]", function($request) {
            $this->view("Flow.latte", ["flow" => $request->name]);
        });
    }
}