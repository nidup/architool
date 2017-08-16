<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\CodeNamespace;

final class ReconfigureSpecNamespaceHandler
{
    private $configurator;

    public function __construct(SpecNamespaceConfigurator $configurator)
    {
        $this->configurator = $configurator;
    }

    public function handle(ReconfigureSpecNamespace $command): void
    {
        $from = new CodeNamespace($command->getLegacyNamespace());
        $to = new CodeNamespace($command->getDestinationNamespace());
        $this->configurator->reconfigure($from, $to);
    }
}
