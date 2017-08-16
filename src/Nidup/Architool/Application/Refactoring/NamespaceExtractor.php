<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\CodeNamespace;

interface NamespaceExtractor
{
    public function extract(CodeNamespace $source, CodeNamespace $destination);
}
