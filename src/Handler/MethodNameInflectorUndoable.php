<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 29/04/2021
 * Time: 23:35
 */

namespace Joselfonseca\LaravelTactician\Handler;

use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

/**
 * Deduce the method name to call on the command handler based on the command
 * and handler instances.
 */
interface MethodNameInflectorUndoable extends MethodNameInflector
{
    /**MethodNameInflector
     * Return the method name to call on the command handler and return it.
     *
     * @param object $command
     * @param object $commandHandler
     *
     * @return string
     */
    public function inflectUndo($command, $commandHandler);

    public function inflectRedo($command, $commandHandler);
}
