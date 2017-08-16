<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\CodeNamespace;

interface NamespaceRenamer
{
    public function rename(CodeNamespace $source, CodeNamespace $destination);
}
