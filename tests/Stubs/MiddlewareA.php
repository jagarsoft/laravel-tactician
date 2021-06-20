<?php

namespace Joselfonseca\LaravelTactician\Tests\Stubs;

use Joselfonseca\LaravelTactician\Middleware\MiddlewareUndoable;

class MiddlewareA implements MiddlewareUndoable {

    private $onDebug = false;

    public function execute($command, callable $next)
    {
        $this->echoDebug(get_class($command).PHP_EOL);
        $this->echoDebug("Antes de MiddlewareA\n");
        $command->response[0] = 'HandledA';
        $this->echoDebug("Resp = ");
        $this->echoDebug(var_export($command->response, true));
        $next($command);
        $this->echoDebug("Despues de MiddlewareA\n");
        return $command;
    }

    public function executeUndo($command, callable $next):void
    {
        $this->echoDebug(get_class($command).PHP_EOL);
        $this->echoDebug("Undo Antes de MiddlewareA\n");
        $command->response[5] = 'UnhandledA';
        $this->echoDebug("Resp = ");
        $this->echoDebug(var_export($command->response, true));
        $next($command);
        $this->echoDebug("Undo Despues de MiddlewareA\n");
    }

    public function executeRedo($command, callable $next):void
    {
        $this->echoDebug(get_class($command).PHP_EOL);
        $this->echoDebug("Antes de MiddlewareA\n");
        $command->response[0] = 'HandledA';
        $this->echoDebug("Resp = ");
        $this->echoDebug(var_export($command->response, true));
        $next($command);
        $this->echoDebug("Despues de MiddlewareA\n");
    }

    private function echoDebug($msg){
        if( $this->onDebug )
            echo $msg;
    }
}