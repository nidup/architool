<?php

namespace Nidup\Architool\Application;

use Nidup\Architool\Domain\ClassName;
use Nidup\Architool\Domain\CodeNamespace;

interface ClassRenamer
{
    public function rename(CodeNamespace $source, CodeNamespace $destination, ClassName $className);
}
