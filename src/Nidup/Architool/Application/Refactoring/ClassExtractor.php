<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\Model\ClassFile\ClassName;
use Nidup\Architool\Domain\CodeNamespace;

// TODO: to drop once unused by MoveLegacySpecHandler
interface ClassExtractor
{
    public function extract(CodeNamespace $source, CodeNamespace $destination, ClassName $class);
}
