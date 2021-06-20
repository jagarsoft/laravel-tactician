<?php

namespace Joselfonseca\LaravelTactician\Tests\Stubs;


class TestCommandSecondHandler
{
    public function handle($command)
    {
        return $command;
    }
}