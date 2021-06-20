<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 29/04/2021
 * Time: 23:49
 */


namespace Joselfonseca\LaravelTactician\Handler;

use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Joselfonseca\LaravelTactician\Handler\MethodNameInflectorUndoable;

/**
 * Handle command by calling the "handle" method.
 */
class HandleInflectorUndoable extends HandleInflector implements MethodNameInflectorUndoable
{
    /**
     * {@inheritdoc}
     */
    public function inflectUndo($command, $commandHandler)
    {
        return 'executeUndo';
    }

    /**
     * {@inheritdoc}
     */
    public function inflectRedo($command, $commandHandler)
    {
        return 'executeRedo';
    }
}
