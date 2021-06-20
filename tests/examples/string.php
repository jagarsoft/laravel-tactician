<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 17/04/2021
 * Time: 22:53
 */

namespace Joselfonseca\LaravelTactician\Tests\Examples\String;

require __DIR__ . '/vendor/autoload.php';

use Joselfonseca\LaravelTactician\BusUndoable;
use Joselfonseca\LaravelTactician\Handler\ClassNameExtractorUndoable;
use Joselfonseca\LaravelTactician\Handler\HandleInflectorUndoable;
use Joselfonseca\LaravelTactician\Locator\LaravelLazyLocator;

class PressKeyCommand
{
    public $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }
}

class PressKeyCommandHandler
{
    private const DELAY = 550000;

    public function handle($command)
    {
        echo $command->key;
        usleep(self::DELAY);
        return $command;
    }

    public function executeUndo($command): void
    {
        echo chr(8).' '.chr(8);
        usleep(self::DELAY);
    }

    public function executeRedo($command)
    {
        echo $command->key;
        usleep(self::DELAY);
    }
}

$exampleStrA = "Hello World!";
$exampleLenA = strlen($exampleStrA);
$exampleStrB = "i Folks!";
$exampleLenB = strlen($exampleStrB);
$exampleLenRedo = $exampleLenA - 1;

$bus = new BusUndoable(
    new HandleInflectorUndoable(),
    new ClassNameExtractorUndoable(),
    new LaravelLazyLocator()
);

$bus->addHandler(PressKeyCommand::class,
                 PressKeyCommandHandler::class);

for($i=0; $i<$exampleLenA; $i++){
    $bus->dispatch(PressKeyCommand::class,
        [
            'key' => substr($exampleStrA, $i, 1)
        ]);
}

$bus->dump();

for($i=0; $i<$exampleLenRedo; $i++){
    $bus->undo();
}

$bus->dump();

for($i=0; $i<$exampleLenRedo; $i++){
    $bus->redo();
}

$bus->dump();

for($i=0; $i<$exampleLenRedo; $i++){
    $bus->undo();
}

$bus->dump();

for($i=0; $i<$exampleLenB; $i++){
    $bus->dispatch(PressKeyCommand::class,
        [
            'key' => substr($exampleStrB, $i, 1)
        ]);
}
