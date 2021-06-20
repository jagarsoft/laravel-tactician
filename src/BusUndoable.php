<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/02/2021
 * Time: 3:11
 */

namespace Joselfonseca\LaravelTactician;


use Joselfonseca\LaravelTactician\Handler\MethodNameInflectorUndoable;
use Joselfonseca\LaravelTactician\Handler\CommandNameExtractorUndoable;
use Joselfonseca\LaravelTactician\Locator\LocatorInterface;
use Joselfonseca\LaravelTactician\Middleware\LockingMiddlewareUndoable;
use Joselfonseca\LaravelTactician\Handler\CommandHandlerMiddlewareUndoable;
use Joselfonseca\LaravelTactician\Exception\CommandHistoryIsEmptyException;
use Joselfonseca\LaravelTactician\Exception\CommandHistoryWasEmptiedException;
use Joselfonseca\LaravelTactician\Exception\RedoCommandHistoryIsEmptyException;

/**
 * The default Command bus Using Tactician, this is an implementation to dispatch commands to their handlers
 * trough a middleware stack, every class is resolved from the laravel's service container.
 *
 * @package Joselfonseca\LaravelTactician
 */
class BusUndoable extends Bus implements CommandBusUndoableInterface
{
    private $commandHistoryCounter = -1; // history is empty
    private $reDoCommandHistoryCounter = -1; // redo history is empty
    private $commandHistory = [];
    private $reDoCommandHistory = [];

    public function __construct(
        MethodNameInflectorUndoable $MethodNameInflector,
        CommandNameExtractorUndoable $CommandNameExtractor,
        LocatorInterface $HandlerLocator
    ) {
        parent::__construct($MethodNameInflector, $CommandNameExtractor, $HandlerLocator);

        // $this->commandHistoryCounter = $this->reDoCommandHistoryCounter = -1;
    }

    /**
     * Dispatch a command and recall it
     *
     * @param  object $command    Command to be dispatched
     * @param  array  $input      Array of input to map to the command
     * @param  array  $middleware Array of middleware class name to add to the stack, they are resolved from the laravel container
     * @return mixed
     */
    public function dispatch($command, array $input = [], array $middleware = [])
    {
        $commandDispatched = parent::dispatch($command, $input, $middleware);

        $this->pushCommandHistory( [clone $commandDispatched, $middleware] ); // Memento Pattern

        return $commandDispatched;
    }

    private function pushCommandHistory (array $commandDispatchedAndMiddleware)
    {
        $this->commandHistory[++$this->commandHistoryCounter] = $commandDispatchedAndMiddleware;
    }

    public function undo()
    {
        try{
            $commandDispatchedAndMiddleware = $this->popCommandHistory();
            $this->pushRedoCommandHistory($commandDispatchedAndMiddleware);

            $this->handleTheUndoCommand($commandDispatchedAndMiddleware[0], $commandDispatchedAndMiddleware[1]);

            return $this->topCommandHistory()[0];

        }catch (CommandHistoryWasEmptiedException $exception){}
    }

    private function popCommandHistory()
    {
        if ($this->commandHistoryCounter == -1) {
            throw new CommandHistoryIsEmptyException();
        }

        $commandDispatchedAndMiddleware = $this->commandHistory[$this->commandHistoryCounter];
        unset($this->commandHistory[$this->commandHistoryCounter]);
        --$this->commandHistoryCounter;

        return $commandDispatchedAndMiddleware;
    }

    private function topCommandHistory()
    {
        if ($this->commandHistoryCounter == -1){
            throw new CommandHistoryWasEmptiedException();
        }

        return $this->commandHistory[$this->commandHistoryCounter];
    }

    private function pushRedoCommandHistory(array $commandDispatchedAndMiddleware)
    {
        $this->reDoCommandHistory[++$this->reDoCommandHistoryCounter] = $commandDispatchedAndMiddleware;
    }

    private function handleTheUndoCommand($commandDispatched, array $middleware): void
    {
        $this->bus = new CommandBusUndoable(
            array_merge(
                [new LockingMiddlewareUndoable()],
                $this->resolveMiddleware(array_reverse($middleware)), // in reversed order
                [new CommandHandlerMiddlewareUndoable($this->CommandNameExtractor, $this->HandlerLocator, $this->MethodNameInflector)]
            )
        );
        $this->bus->handlerUndo($commandDispatched);
    }

    public function redo()
    {
        $commandDispatchedAndMiddleware = $this->popRedoCommandHistory();
        $this->pushCommandHistory($commandDispatchedAndMiddleware);

        $this->handleTheRedoCommand($commandDispatchedAndMiddleware[0], $commandDispatchedAndMiddleware[1]);

        return $commandDispatchedAndMiddleware[0];
    }

    private function popRedoCommandHistory()
    {
        if( $this->reDoCommandHistoryCounter == -1 ) {
            throw new RedoCommandHistoryIsEmptyException();
        }

        $commandDispatchedAndMiddleware = $this->reDoCommandHistory[$this->reDoCommandHistoryCounter];
        unset($this->reDoCommandHistory[$this->reDoCommandHistoryCounter]);
        --$this->reDoCommandHistoryCounter;

        return $commandDispatchedAndMiddleware;
    }

    private function handleTheRedoCommand($commandDispatched, $middleware): void
    {
        $this->bus = new CommandBusUndoable(
            array_merge(
                [new LockingMiddlewareUndoable()],
                $this->resolveMiddleware($middleware), // in original order again !!!!
                [new CommandHandlerMiddlewareUndoable($this->CommandNameExtractor, $this->HandlerLocator, $this->MethodNameInflector)]
            )
        );
        $this->bus->handlerRedo($commandDispatched);
    }

    public function clearHistory()
    {
        $this->commandHistory = [];
        $this->reDoCommandHistory = [];
        $this->commandHistoryCounter = $this->reDoCommandHistoryCounter = -1;
    }

    public function dump()
    {return;
        echo PHP_EOL.'commandHistoryCounter: '.$this->commandHistoryCounter;
        echo PHP_EOL.'reDoCommandHistoryCounter: '.$this->reDoCommandHistoryCounter.PHP_EOL;
    }
}