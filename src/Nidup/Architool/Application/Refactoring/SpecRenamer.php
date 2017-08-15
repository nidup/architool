<?php

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\ClassName;
use Nidup\Architool\Domain\CodeNamespace;

interface SpecRenamer
{
    public function rename(CodeNamespace $source, CodeNamespace $destination, ClassName $className);
}
