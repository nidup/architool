<?php

namespace Nidup\Architool\Application;

use Nidup\Architool\Domain\CodeNamespace;

interface NamespaceExtractor
{
    public function extract(CodeNamespace $source, CodeNamespace $destination);
}
