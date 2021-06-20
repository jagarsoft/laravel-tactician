<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/03/2021
 * Time: 13:33
 */

namespace Joselfonseca\LaravelTactician\Middleware;

use League\Tactician\Middleware;

interface MiddlewareUndoable extends Middleware
{
    /**
     * @param object   $command
     * @param callable $next
     *
     * @return void
     */
    public function executeUndo($command, callable $next): void;

    /**
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     */
    public function executeRedo($command, callable $next): void;
}