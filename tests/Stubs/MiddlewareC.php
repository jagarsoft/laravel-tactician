<?php

namespace Joselfonseca\LaravelTactician\Tests\Stubs;

use Joselfonseca\LaravelTactician\Middleware\MiddlewareUndoable;

class MiddlewareC implements MiddlewareUndoable{

    private $onDebug = false;

    public function execute($command, callable $next)
    {
        $this->echoDebug(get_class($command).PHP_EOL);
        $this->echoDebug("Antes de MiddlewareC\n");
        $command->response[2] = 'HandledC';
        $this->echoDebug("Resp = ");
        $this->echoDebug(var_export($command->response, true));
        $next($command);
        $this->echoDebug("Despues de MiddlewareC\n");
        return $command;
    }

    public function executeUndo($command, callable $next):void
    {
        $this->echoDebug(get_class($command).PHP_EOL);
        $this->echoDebug("Undo Antes de MiddlewareC\n");
        $command->response[3] = 'UnhandledC';
        $this->echoDebug("Resp = ");
        $this->echoDebug(var_export($command->response, true));
        $next($command);
        $this->echoDebug("Undo Despues de MiddlewareC\n");
    }

    public function executeRedo($command, callable $next):void
    {
        $this->echoDebug(get_class($command).PHP_EOL);
        $this->echoDebug("Antes de MiddlewareC\n");
        $command->response[2] = 'HandledC';
        $this->echoDebug("Resp = ");
        $this->echoDebug(var_export($command->response, true));
        $next($command);
        $this->echoDebug("Despues de MiddlewareC\n");
    }

    private function echoDebug($msg){
        if( $this->onDebug )
            echo $msg;
    }
}