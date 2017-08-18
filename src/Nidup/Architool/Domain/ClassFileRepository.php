<?php

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\ClassFile;
use Nidup\Architool\Domain\Model\ClassFile\ClassNamespace;
use Nidup\Architool\Domain\Model\File\Name;

interface ClassFileRepository
{
    public function get(ClassNamespace $namespace, Name $name): ClassFile;

    public function update(ClassFile $class);
}