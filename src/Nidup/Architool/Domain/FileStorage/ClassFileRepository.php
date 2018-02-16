<?php

namespace Nidup\Architool\Domain\FileStorage;

use Nidup\Architool\Domain\FileStorage\ClassFile\ClassNamespace;
use Nidup\Architool\Domain\FileStorage\File\Name;

interface ClassFileRepository
{
    public function get(ClassNamespace $namespace, Name $name): ClassFile;

    public function update(ClassFile $class);
}