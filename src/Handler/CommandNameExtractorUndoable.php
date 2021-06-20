<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 25/04/2021
 * Time: 14:14
 */

namespace Joselfonseca\LaravelTactician\Handler;

use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Exception\CanNotDetermineCommandNameException;

/**
 * Extract the name from a command so that the name can be determined
 * by the context better than simply the class name
 */
interface CommandNameExtractorUndoable extends CommandNameExtractor
{
    /**
     * Extract the name from a command
     *
     * @param object $command
     *
     * @return string
     *
     * @throws CannotDetermineCommandNameException
     */
    public function extractUndo($command);

    public function extractRedo($command);
}