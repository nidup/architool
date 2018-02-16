<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\FileStorage\ClassFile;
use Nidup\Architool\Domain\FileStorage\ClassFile\ClassNamespace;
use Nidup\Architool\Domain\FileStorage\ClassFileRepository;
use Nidup\Architool\Domain\FileStorage\File\Name;

class FsClassFileRepository implements ClassFileRepository
{
    private $mover;
    private $updater;

    public function __construct(FileMover $mover, ClassFileReferenceUpdater $updater)
    {
        $this->mover = $mover;
        $this->updater = $updater;
    }

    public function get(ClassNamespace $namespace, Name $name): ClassFile
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
                    $class->getNamespace()->getName(),
                    $class->getName()->getValue()
                )
            );
        }
    }
}
