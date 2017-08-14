<?php

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\CodeNamespace;

interface SpecNamespaceConfigurator
{
    public function configure(CodeNamespace $from, CodeNamespace $to);
}