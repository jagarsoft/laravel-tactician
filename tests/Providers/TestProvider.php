<?php

namespace Joselfonseca\LaravelTactician\Tests\Providers;


use Joselfonseca\LaravelTactician\Tests\TestCase;

/**
 * Class TestProvider
 * @package Joselfonseca\LaravelTactician\Tests\Providers
 */
class TestProvider extends TestCase{

    /**
     * It loads the service Provider
     */
    public function test_it_loads_service_provider()
    {
        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Providers\LaravelTacticianServiceProvider',
             app()->getProvider('Joselfonseca\LaravelTactician\Providers\LaravelTacticianServiceProvider'));
    }

    /**
     * it registers a locator
     */
    public function test_it_registers_locator()
    {
        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Locator\LocatorInterface',
                            app('Joselfonseca\LaravelTactician\Locator\LocatorInterface'));
    }

    /**
     * It registers the inflector
     */
    public function test_it_registers_inflector()
    {
        $this->assertInstanceOf('League\Tactician\Handler\MethodNameInflector\MethodNameInflector',
                            app('League\Tactician\Handler\MethodNameInflector\MethodNameInflector'));
    }

    /**
     * It registers the inflector
     */
    public function test_it_registers_inflector_undoable()
    {
        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Handler\MethodNameInflectorUndoable',
            app('Joselfonseca\LaravelTactician\Handler\MethodNameInflectorUndoable'));
    }

    /**
     * it registers the extractor
     */
    public function test_it_registers_extractor()
    {
        $this->assertInstanceOf('League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor',
                            app('League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor'));
    }

    /**
     * it registers the extractor
     */
    public function test_it_registers_extractor_undoable()
    {
        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Handler\CommandNameExtractorUndoable',
            app('Joselfonseca\LaravelTactician\Handler\CommandNameExtractorUndoable'));
    }
}