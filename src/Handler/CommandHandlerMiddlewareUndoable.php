<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/03/2021
 * Time: 14:12
 */

namespace Joselfonseca\LaravelTactician\Handler;

use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Exception\CanNotInvokeHandlerException;
use Joselfonseca\LaravelTactician\Middleware\MiddlewareUndoable;
//use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use Joselfonseca\LaravelTactician\Handler\CommandNameExtractorUndoable;
use League\Tactician\Handler\Locator\HandlerLocator;
//use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use Joselfonseca\LaravelTactician\Handler\MethodNameInflectorUndoable;


class CommandHandlerMiddlewareUndoable implements MiddlewareUndoable
{
    /**
     * @var CommandNameExtractorUndoable
     */
    private $commandNameExtractor;

    /**
     * @var HandlerLocator
     */
    private $handlerLocator;

    /**
     * @var MethodNameInflectorUndoable
     */
    private $methodNameInflector;

    /**
     * @param CommandNameExtractorUndoable $commandNameExtractor
     * @param HandlerLocator       $handlerLocator
     * @param MethodNameInflectorUndoable  $methodNameInflector
     */
    // Liskov's Principle is L for SOLID, applies here
    public function __construct(
        CommandNameExtractorUndoable $commandNameExtractor,
        HandlerLocator $handlerLocator,
        MethodNameInflectorUndoable $methodNameInflector
    ) {
        $this->commandNameExtractor = $commandNameExtractor;
        $this->handlerLocator = $handlerLocator;
        $this->methodNameInflector = $methodNameInflector;
    }

    public function execute($command, callable $next)
    {
        throw new \InvalidArgumentException();
    }

    public function executeUndo($command, callable $next): void
    {
        //return $this->execute($command, $next);
        $commandName = $this->commandNameExtractor->extractUndo($command);
        $handler = $this->handlerLocator->getHandlerForCommand($commandName);
        $methodName = $this->methodNameInflector->inflectUndo($command, $handler);
        //$methodName = 'executeUndo';

        // is_callable is used here instead of method_exists, as method_exists
        // will fail when given a handler that relies on __call.
        if (!is_callable([$handler, $methodName])) {
            throw CanNotInvokeHandlerException::forCommand(
                $command,
                "Method '{$methodName}' does not exist on handler"
            );
        }

        $handler->{$methodName}($command);
    }

    public function executeRedo($command, callable $next):void
    {
        //return $this->execute($command, $next);
        $commandName = $this->commandNameExtractor->extractRedo($command);
        $handler = $this->handlerLocator->getHandlerForCommand($commandName);
        $methodName = $this->methodNameInflector->inflectRedo($command, $handler);
        //$methodName = 'executeRedo';

        // is_callable is used here instead of method_exists, as method_exists
        // will fail when given a handler that relies on __call.
        if (!is_callable([$handler, $methodName])) {
            throw CanNotInvokeHandlerException::forCommand(
                $command,
                "Method '{$methodName}' does not exist on handler"
            );
        }

        $handler->{$methodName}($command);
    }
}