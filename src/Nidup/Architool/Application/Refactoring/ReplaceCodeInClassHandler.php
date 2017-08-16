<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactoring;

use Nidup\Architool\Domain\ClassName;
use Nidup\Architool\Domain\CodeFragment;
use Nidup\Architool\Domain\CodeNamespace;

final class ReplaceCodeInClassHandler
{
    private $replacer;

    public function __construct(CodeReplacer $replacer)
    {
        $this->replacer = $replacer;
    }

    public function handle(ReplaceCodeInClass $command): void
    {
        $namespace = new CodeNamespace($command->getNamespace());
        $class = new ClassName($command->getClassName());
        $legacyCode = new CodeFragment($command->getLegacyCode());
        $replacementCode = new CodeFragment($command->getReplacementCode());
        $this->replacer->replace($namespace, $class, $legacyCode, $replacementCode);
    }
}
