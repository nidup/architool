<?php

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\CodeNamespace;

final class ConfigureSpecNamespaceHandler
{
    private $configurator;

    public function __construct(SpecNamespaceConfigurator $configurator)
    {
        $this->configurator = $configurator;
    }

    public function handle(ConfigureSpecNamespace $command): void
    {
        $from = new CodeNamespace($command->getLegacyNamespace());
        $to = new CodeNamespace($command->getDestinationNamespace());
        $this->configurator->configure($from, $to);
    }
}
