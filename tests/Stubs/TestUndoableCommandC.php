<?php

namespace Joselfonseca\LaravelTactician\Tests\Stubs;


class TestUndoableCommandC {

    public $property;

    public $propertyTwo;

    public function __construct($property = null, $propertyTwo = "First Name")
    {
        $this->property = $property;
        $this->propertyTwo = $propertyTwo;
    }


}