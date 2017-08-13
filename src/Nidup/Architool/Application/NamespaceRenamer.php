<?php

namespace Nidup\Architool\Application;

use Nidup\Architool\Domain\CodeNamespace;

interface NamespaceRenamer
{
    public function rename(CodeNamespace $source, CodeNamespace $destination);
}
