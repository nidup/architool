<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\Model\ClassFile\ClassName;
use Nidup\Architool\Domain\CodeNamespace;

final class MoveLegacySpecHandler
{
    private $extractor;
    private $renamer;

    public function __construct(ClassExtractor $extractor, SpecRenamer $renamer)
    {
        $this->extractor = $extractor;
        $this->renamer = $renamer;
    }

    public function handle(MoveLegacySpec $command): void
    {
        $from = new CodeNamespace($command->getLegacyNamespace());
        $to = new CodeNamespace($command->getDestinationNamespace());
        $class = new ClassName($command->getClassName());
        $this->extractor->extract($from, $to, $class);
        $this->renamer->rename($from, $to, $class);
    }
}
