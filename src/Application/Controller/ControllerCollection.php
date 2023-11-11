<?php

namespace Automator\Application\Controller;

class ControllerCollection {

    private array $list;

    public function __construct(array $list = [])
    {
        $this->list = $list;
    }

    public function add(Controller $controller) {
        $this->list[] = $controller;
    }

    public function toArray(): array {
        return $this->list;
    }

    public function each(callable $func) {
        foreach($this->list as $item) {
            $func($item);
        }
    }

}