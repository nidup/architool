<?php

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\ClassFile;
use Nidup\Architool\Domain\Model\ClassFile\ClassName;
use Nidup\Architool\Domain\Model\ClassFile\ClassNamespace;

interface ClassFileRepository
{
    public function get(ClassNamespace $namespace, ClassName $name): ClassFile;

    public function update(ClassFile $class);
}