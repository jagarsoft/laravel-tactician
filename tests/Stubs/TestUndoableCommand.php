<?php

namespace Joselfonseca\LaravelTactician\Tests\Stubs;


class TestUndoableCommand {

    public $property;

    public $propertyTwo;

    public function __construct($property = null, $propertyTwo = "First Name")
    {
        $this->property = $property;
        $this->propertyTwo = $propertyTwo;
    }


}