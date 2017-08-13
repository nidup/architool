<?php

namespace Nidup\Architool\Application;

use Nidup\Architool\Domain\CodeNamespace;

final class MoveLegacyNamespaceHandler
{
    private $extractor;

    public function __construct(NamespaceExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    public function handle(MoveLegacyNamespace $command): void
    {
        $from = new CodeNamespace($command->getLegacyNamespace());
        $to = new CodeNamespace($command->getDestinationNamespace());
        $this->extractor->extract($from, $to);
    }
}
