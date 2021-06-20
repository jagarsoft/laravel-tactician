<?php

namespace Joselfonseca\LaravelTactician\Tests\Locator;

use Joselfonseca\LaravelTactician\Tests\TestCase;
use Illuminate\Contracts\Container\BindingResolutionException;
use Joselfonseca\LaravelTactician\Locator\LaravelLocator;

/**
 * Class TestLaravelLocator
 * @package Joselfonseca\LaravelTactician\Tests\Locator
 */
class TestLaravelLocator extends TestCase{

    /**
     * It resolves the locator
     */
    public function test_it_resolves_the_laravel_locator()
    {
        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Locator\LocatorInterface',
            app('Joselfonseca\LaravelTactician\Locator\LaravelLocator'));
    }

    /**
     * Throws exception if no handler for a command has been added
     */
    public function test_it_throws_exception_when_locator_from_laravel_container_is_not_found()
    {
        $this->expectException(\League\Tactician\Exception\MissingHandlerException::class);
        $locator = app('Joselfonseca\LaravelTactician\Locator\LocatorInterface');
        $handler = $locator->getHandlerForCommand('TestCommand');
    }

    /**
     * Throws exception if laravel container can't resolve the handler class
     */
    public function test_it_throws_exception_when_locator_is_not_resolve_from_laravel_container()
    {
        $this->expectException(BindingResolutionException::class);
        $locator = app(LaravelLocator::class);
        $locator->addHandler('SomeCommandHandler',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestCommand');
    }

    /**
     * It is able to resolve the locator from the container
     */
    public function test_it_is_able_to_resolve_handler_from_laravel_container()
    {
        $locator = app('Joselfonseca\LaravelTactician\Locator\LocatorInterface');
        $locator->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestCommandHandler',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestCommand');
        $handler = $locator->getHandlerForCommand('Joselfonseca\LaravelTactician\Tests\Stubs\TestCommand');
        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestCommandHandler', $handler);
    }

    /**
     * Add more than one command => handler to the bus
     */
    public function test_it_maps_array_commands()
    {
        $locator = app('Joselfonseca\LaravelTactician\Locator\LocatorInterface');
        $locator->addHandlers([
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestCommand' => 'Joselfonseca\LaravelTactician\Tests\Stubs\TestCommandHandler',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestCommandInput' => 'Joselfonseca\LaravelTactician\Tests\Stubs\TestCommandSecondHandler'
        ]);
        $handler = $locator->getHandlerForCommand('Joselfonseca\LaravelTactician\Tests\Stubs\TestCommand');
        $handler2 = $locator->getHandlerForCommand('Joselfonseca\LaravelTactician\Tests\Stubs\TestCommandInput');
        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestCommandHandler', $handler);
        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestCommandSecondHandler', $handler2);
    }
}