<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 03/03/2021
 * Time: 19:13
 */

namespace Joselfonseca\LaravelTactician;

use League\Tactician\CommandBus;
use League\Tactician\Exception\InvalidCommandException;
use League\Tactician\Exception\InvalidMiddlewareException;
use League\Tactician\Middleware;


/**
 * Receives a command and sends it through a chain of middleware for processing.
 *
 * @final
 */
class CommandBusUndoable extends CommandBus // Not really, but L for Liskov in SOLID Principles applies here too
{
    /**
     * @var callable
     */
    private $middlewareUndoChain;

    /**
     * @var callable
     */
    private $middlewareRedoChain;

    /**
     * @param Middleware[] $middleware
     */
    public function __construct(array $middleware)
    {
        $this->middlewareUndoChain = $this->createExecutionChain($middleware, 'executeUndo');
        $this->middlewareRedoChain = $this->createExecutionChain($middleware, 'executeRedo');
    }

    /**
     * Executes the given command and optionally returns a value
     *
     * @param object $command
     *
     * @return mixed
     */
    public function handlerUndo($command)
    {
        if (is_object($command)) {
            return $command;
        }
        if (!is_object($command)) {
            throw InvalidCommandException::forUnknownValue($command);
        }

        return ($this->middlewareUndoChain)($command);
    }

    public function handlerRedo($command)
    {
        if (is_object($command)) {
            return $command;
        }
        if (!is_object($command)) {
            throw InvalidCommandException::forUnknownValue($command);
        }

        return ($this->middlewareRedoChain)($command);
    }

    private function createExecutionChain($middlewareList, $executor)
    {
        $lastCallable = function () {
            // the final callable is a no-op
        };

        while ($middleware = array_pop($middlewareList)) {
            if (! $middleware instanceof Middleware) {
                throw InvalidMiddlewareException::forMiddleware($middleware);
            }

            $lastCallable = function ($command) use ($middleware, $lastCallable, $executor) {
                return $middleware->{$executor}($command, $lastCallable);
            };
        }

        return $lastCallable;
    }
}
