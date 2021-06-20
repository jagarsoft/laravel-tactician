<?php

/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/03/2021
 * Time: 3:10
 */
namespace Joselfonseca\LaravelTactician\Exception;

// private exception for internal use only

class CommandHistoryWasEmptiedException extends \Exception
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
        $exception = new self('Command History Was Emptied by ' . $commandName . ". You shouldn't see this!");
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