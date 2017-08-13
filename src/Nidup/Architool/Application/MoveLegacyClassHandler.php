<?php

namespace Nidup\Architool\Application;

use Nidup\Architool\Domain\ClassName;
use Nidup\Architool\Domain\CodeNamespace;

final class MoveLegacyClassHandler
{
    private $extractor;
    private $renamer;

    public function __construct(ClassExtractor $extractor, ClassRenamer $renamer)
    {
        $this->extractor = $extractor;
        $this->renamer = $renamer;
    }

    public function handle(MoveLegacyClass $command): void
    {
        $from = new CodeNamespace($command->getLegacyNamespace());
        $to = new CodeNamespace($command->getDestinationNamespace());
        $class = new ClassName($command->getClassName());
        $this->extractor->extract($from, $to, $class);
        $this->renamer->rename($from, $to, $class);
    }
}
