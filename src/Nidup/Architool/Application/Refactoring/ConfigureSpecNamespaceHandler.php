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
        $namespace = new CodeNamespace($command->getNamespace());
        $this->configurator->configure($namespace);
    }
}
