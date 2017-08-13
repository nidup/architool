<?php

namespace Nidup\Architool\Application;

use Nidup\Architool\Domain\ClassName;
use Nidup\Architool\Domain\CodeNamespace;

interface ClassExtractor
{
    public function extract(CodeNamespace $source, CodeNamespace $destination, ClassName $class);
}
