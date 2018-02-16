<?php

namespace Nidup\Architool\Domain\FileStorage;

use Nidup\Architool\Domain\FileStorage\File\Name;
use Nidup\Architool\Domain\FileStorage\SpecFile\SpecNamespace;

interface SpecFileRepository
{
    public function get(SpecNamespace $namespace, Name $name): SpecFile;

    public function update(SpecFile $spec);
}
