<?php

namespace Automator\Application\Controller;

use Klein\Klein;
use Latte\Engine;

abstract class Controller
{

    public abstract function registerRoute(Klein $klein): void;

    public function view(string $template, array $params = [])
    {
        $latte = new Engine();
        $latte->setTempDirectory(__DIR__ . "/../View/cache/");

        $output = $latte->renderToString(__DIR__ . "/../View/" . $template, $params);
        echo $output;
    }

    private function status(int $code) {
        http_send_status($code);
    }

}