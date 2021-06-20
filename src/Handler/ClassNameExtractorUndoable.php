<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 26/04/2021
 * Time: 23:22
 */

namespace Joselfonseca\LaravelTactician\Handler;

use League\Tactician\Exception\CanNotDetermineCommandNameException;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;


/**
 * Extract the name from the class
 */
// Liskov's Principle is L for SOLID, applies here
class ClassNameExtractorUndoable extends ClassNameExtractor implements CommandNameExtractorUndoable
{
    /**
     * {@inheritdoc}
     */
    public function extractUndo($command)
    {
        return get_class($command);
    }

    /**
     * {@inheritdoc}
     */
    public function extractRedo($command)
    {
        return get_class($command);
    }
}