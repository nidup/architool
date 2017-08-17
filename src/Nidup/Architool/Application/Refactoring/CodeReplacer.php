<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\Model\ClassFile\ClassName;
use Nidup\Architool\Domain\CodeFragment;
use Nidup\Architool\Domain\CodeNamespace;

interface CodeReplacer
{
    public function replace(
        CodeNamespace $namespace,
        ClassName $class,
        CodeFragment $legacyCode,
        CodeFragment $replacementCode
    );
}
