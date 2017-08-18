<?php

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\File\Name;
use Nidup\Architool\Domain\Model\SpecFile;
use Nidup\Architool\Domain\Model\SpecFile\SpecNamespace;

interface SpecFileRepository
{
    public function get(SpecNamespace $namespace, Name $name): SpecFile;

    public function update(SpecFile $spec);
}
