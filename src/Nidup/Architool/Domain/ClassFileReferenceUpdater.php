<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\ClassFile;

interface ClassFileReferenceUpdater
{
    public function update(ClassFile $class);
}
