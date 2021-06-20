<?php

namespace Joselfonseca\LaravelTactician\Tests\Stubs;


class TestUndoableCommandHandlerA {

    public function handle($command)
    {
        return $command;
    }

    public function executeUndo($command)
    {

    }

    public function executeRedo($command)
    {

    }
}