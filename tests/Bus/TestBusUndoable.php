<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/02/2021
 * Time: 16:37
 */

namespace Joselfonseca\LaravelTactician\Tests\Bus;

use Joselfonseca\LaravelTactician\Tests\TestCase;
use Joselfonseca\LaravelTactician\Exception\CommandHistoryIsEmptyException;
use Joselfonseca\LaravelTactician\Exception\RedoCommandHistoryIsEmptyException;


/**
 * Class TestBusUndoable
 * @package Joselfonseca\LaravelTactician\Tests\Bus
 */
class TestBusUndoable extends TestCase{

    public function test_it_throws_CommandHistoryIsEmptyException_if_nothing_to_undo(){
        $bus = app('Joselfonseca\LaravelTactician\CommandBusUndoableInterface');

        $this->expectException(CommandHistoryIsEmptyException::class);
        $bus->undo();
    }

    public function test_it_has_nothing_to_return_when_only_a_command_to_undo()
    {
        $bus = app('Joselfonseca\LaravelTactician\CommandBusUndoableInterface');

        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
                         'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerA');

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
                 $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA', [], []));

        $this->assertNotInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->undo() );
    }

    public function test_it_handles_undo_last_command_when_two_commands_were_dispatched()
    {
        $bus = app('Joselfonseca\LaravelTactician\CommandBusUndoableInterface');

        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerA');

        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerB');

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA', [], []));

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB',
            $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB', [], []));

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->undo() );
    }

    public function test_redo_one_command_returns_the_same_as_that_command()
    {
        $bus = app('Joselfonseca\LaravelTactician\CommandBusUndoableInterface');

        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerA');

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $expectedCommandA = $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA', [], []));

        $this->assertNotInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->undo() );

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $redoCommandA = $bus->redo('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA', [], []));

        $this->assertEquals($expectedCommandA, $redoCommandA);
    }

    public function test_redo_two_commands_return_the_same_as_that_previous_command()
    {
        $bus = app('Joselfonseca\LaravelTactician\CommandBusUndoableInterface');

        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerA');

        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerB');

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA', [], []));

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB',
            $expectedCommandB = $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB', [], []));

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->undo() );

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB',
            $redoCommandB = $bus->redo() );

        $this->assertEquals($expectedCommandB, $redoCommandB);
    }

    /**
     * Test if the class can handle a undoable command in reversed order
     */
    public function test_it_applies_a_middleware_chain_reversed()
    {
        $bus = app('Joselfonseca\LaravelTactician\CommandBusUndoableInterface');

        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerA');
        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerB');
        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandC',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerC');

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $commandA = $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA', [], [
                'Joselfonseca\LaravelTactician\Tests\Stubs\MiddlewareA',
                'Joselfonseca\LaravelTactician\Tests\Stubs\MiddlewareB',
                'Joselfonseca\LaravelTactician\Tests\Stubs\MiddlewareC',
            ]));

        $this->assertSame([
            0 => 'HandledA',
            1 => 'HandledB',
            2 => 'HandledC'
        ], $commandA->response);

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB',
            $commandB = $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB', [], [
                'Joselfonseca\LaravelTactician\Tests\Stubs\MiddlewareA',
                'Joselfonseca\LaravelTactician\Tests\Stubs\MiddlewareB',
                'Joselfonseca\LaravelTactician\Tests\Stubs\MiddlewareC',
            ]));

        $this->assertSame([
            0 => 'HandledA',
            1 => 'HandledB',
            2 => 'HandledC'
        ], $commandB->response);

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandC',
            $commandC = $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandC', [], [
                'Joselfonseca\LaravelTactician\Tests\Stubs\MiddlewareA',
                'Joselfonseca\LaravelTactician\Tests\Stubs\MiddlewareB',
                'Joselfonseca\LaravelTactician\Tests\Stubs\MiddlewareC',
            ]));

        $this->assertSame([
            0 => 'HandledA',
            1 => 'HandledB',
            2 => 'HandledC'
        ], $commandC->response);

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandB',
            $undoneCommandCreturnsCommandB = $bus->undo() );

        $this->assertSame([
            0 => 'HandledA',
            1 => 'HandledB',
            2 => 'HandledC'
        ], $undoneCommandCreturnsCommandB->response);

        $this->assertEquals($commandB, $undoneCommandCreturnsCommandB);

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->undo() );

        $this->assertNotInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->undo() );
    }

    public function test_it_can_empty_command_history()
    {
        $bus = app('Joselfonseca\LaravelTactician\CommandBusUndoableInterface');

        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerA');

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA', [], []));

        $this->assertNotInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->undo() );

        $bus->clearHistory();

        $this->expectException(CommandHistoryIsEmptyException::class);
        $bus->undo();

        $this->expectException(RedoCommandHistoryIsEmptyException::class);
        $bus->redo();
    }

    public function test_redo_history_is_empty()
    {
        $bus = app('Joselfonseca\LaravelTactician\CommandBusUndoableInterface');

        $bus->addHandler('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            'Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandHandlerA');

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->dispatch('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA', [], []));

        $this->assertNotInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->undo() );

        $this->assertInstanceOf('Joselfonseca\LaravelTactician\Tests\Stubs\TestUndoableCommandA',
            $bus->redo() );

        $this->expectException(RedoCommandHistoryIsEmptyException::class);
        $bus->redo();
    }
}