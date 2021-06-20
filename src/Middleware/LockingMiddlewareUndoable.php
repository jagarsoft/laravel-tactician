<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/03/2021
 * Time: 13:45
 */

namespace Joselfonseca\LaravelTactician\Middleware;

use League\Tactician\Plugins\LockingMiddleware;


class LockingMiddlewareUndoable extends LockingMiddleware implements MiddlewareUndoable
{
    public function executeUndo($command, callable $next): void
    {
        $this->execute($command, $next);
    }

    public function executeRedo($command, callable $next):void
    {
        $this->execute($command, $next);
    }
}