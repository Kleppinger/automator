<?php

namespace Automator\Application\Controller;

use Automator\Application\Controller\Controller;
use Klein\Klein;

class AvatarController extends Controller
{

    public function registerRoute(Klein $klein): void
    {
        $klein->get("/avatar/[:name]", function($request) {
            $avatar = new Init();
        });
    }
}