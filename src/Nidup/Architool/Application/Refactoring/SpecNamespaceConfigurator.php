<?php

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\CodeNamespace;

interface SpecNamespaceConfigurator
{
    public function reconfigure(CodeNamespace $fromNamespace, CodeNamespace $toNamespace);

    public function configure(CodeNamespace $newNamespace);
}
