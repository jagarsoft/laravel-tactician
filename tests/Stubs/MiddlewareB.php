<?php

namespace Joselfonseca\LaravelTactician\Tests\Stubs;

use Joselfonseca\LaravelTactician\Middleware\MiddlewareUndoable;

class MiddlewareB implements MiddlewareUndoable{

    private $onDebug = false;

    public function execute($command, callable $next)
    {
        $this->echoDebug(get_class($command).PHP_EOL);
        $this->echoDebug("Antes de MiddlewareB\n");
        $command->response[1] = 'HandledB';
        $this->echoDebug("Resp = ");
        $this->echoDebug(var_export($command->response, true));
        $next($command);
        $this->echoDebug("Despues de MiddlewareB\n");
        return $command;
    }

    public function executeUndo($command, callable $next):void
    {
        $this->echoDebug(get_class($command).PHP_EOL);
        $this->echoDebug("Undo Antes de MiddlewareB\n");
        $command->response[4] = 'UnhandledB';
        $this->echoDebug("Resp = ");
        $this->echoDebug(var_export($command->response, true));
        $next($command);
        $this->echoDebug("Undo Despues de MiddlewareB\n");
    }

    public function executeRedo($command, callable $next):void
    {
        $this->echoDebug(get_class($command).PHP_EOL);
        $this->echoDebug("Antes de MiddlewareB\n");
        $command->response[1] = 'HandledB';
        $this->echoDebug("Resp = ");
        $this->echoDebug(var_export($command->response, true));
        $next($command);
        $this->echoDebug("Despues de MiddlewareB\n");
    }

    private function echoDebug($msg){
        if( $this->onDebug )
            echo $msg;
    }
}