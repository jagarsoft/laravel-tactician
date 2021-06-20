<?php

/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/03/2021
 * Time: 3:10
 */
namespace Joselfonseca\LaravelTactician\Exception;

//use League\Tactician\Exception\Exception;

use phpDocumentor\Reflection\Types\Self_;

class RedoCommandHistoryIsEmptyException extends \Exception
{
    /**
     * @var string
     */
    private $commandName;

    /**
     * @param string $commandName
     * @see https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static
     *
     * @return static
     */
    public static function forCommand($commandName)
    {
        $exception = new self('Redo Command History Is Empty ' . $commandName);
        $exception->commandName = $commandName;

        return $exception;
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return $this->commandName;
    }
}