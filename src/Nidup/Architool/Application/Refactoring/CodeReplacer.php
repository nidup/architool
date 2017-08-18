<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\CodeFragment;
use Nidup\Architool\Domain\CodeNamespace;
use Nidup\Architool\Domain\Model\File\Name;

interface CodeReplacer
{
    public function replace(
        CodeNamespace $namespace,
        Name $class,
        CodeFragment $legacyCode,
        CodeFragment $replacementCode
    );
}
