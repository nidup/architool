<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\ClassFile;

interface ClassFileMover
{
    public function move(ClassFile $class);
}
