<?php

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Application\Refactoring\NamespaceExtractor;
use Nidup\Architool\Application\Refactoring\NamespaceRenamer;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespace;
use Nidup\Architool\Domain\CodeNamespace;

final class MoveLegacyNamespaceHandler
{
    private $extractor;
    private $renamer;

    public function __construct(NamespaceExtractor $extractor, NamespaceRenamer $renamer)
    {
        $this->extractor = $extractor;
        $this->renamer = $renamer;
    }

    public function handle(MoveLegacyNamespace $command): void
    {
        $from = new CodeNamespace($command->getLegacyNamespace());
        $to = new CodeNamespace($command->getDestinationNamespace());
        $this->extractor->extract($from, $to);
        $this->renamer->rename($from, $to);
    }
}
