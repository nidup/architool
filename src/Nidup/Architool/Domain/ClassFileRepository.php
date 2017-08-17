<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\ClassFile\ClassName;
use Nidup\Architool\Domain\Model\ClassFile\ClassNamespace;
use Nidup\Architool\Domain\Model\ClassFile;

class ClassFileRepository
{
    private $mover;
    private $updater;

    public function __construct(ClassFileMover $mover, ClassFileReferenceUpdater $updater)
    {
        $this->mover = $mover;
        $this->updater = $updater;
    }

    public function get(ClassNamespace $namespace, ClassName $name): ClassFile
    {
        return new ClassFile($namespace, $name);
    }

    public function update(ClassFile $class)
    {
        if ($class->hasMoved()) {
            $this->mover->move($class);
            $this->updater->update($class);
        } else {
            throw new \LogicException(
                sprintf(
                    'Calling update on a not modified file %s/%s',
                    $class->getOriginalNamespace()->getName(),
                    $class->getName()->getName()
                )
            );
        }
    }
}
