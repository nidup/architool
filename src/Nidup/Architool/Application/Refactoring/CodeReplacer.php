<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\Code\CodeFragment;
use Nidup\Architool\Domain\Code\CodeNamespace;
use Nidup\Architool\Domain\FileStorage\File\Name;

interface CodeReplacer
{
    public function replace(
        CodeNamespace $namespace,
        Name $class,
        CodeFragment $legacyCode,
        CodeFragment $replacementCode
    );
}
