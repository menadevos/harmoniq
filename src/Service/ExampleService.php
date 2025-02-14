<?php

namespace App\Service;

class ExampleService
{
    private $someArgument;

    public function __construct($someArgument)  // Notice the constructor with an argument
    {
        $this->someArgument = $someArgument;
    }

    public function doSomething(): string
    {
        return "Service is working with: " . $this->someArgument;
    }
}
