<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\Model\File\Name;
use Nidup\Architool\Domain\Model\SpecFile;
use Nidup\Architool\Domain\Model\SpecFile\SpecNamespace;
use Nidup\Architool\Domain\SpecFileRepository;

class FsSpecFileRepository implements SpecFileRepository
{
    private $mover;
    private $updater;

    public function __construct(FileMover $mover, SpecFileReferenceUpdater $updater)
    {
        $this->mover = $mover;
        $this->updater = $updater;
    }

    public function get(SpecNamespace $namespace, Name $name): SpecFile
    {
        return new SpecFile($namespace, $name);
    }

    public function update(SpecFile $spec)
    {
        if ($spec->hasMoved()) {
            $this->mover->move($spec);
            $this->updater->update($spec);
        } else {
            throw new \LogicException(
                sprintf(
                    'Calling update on a not modified file %s/%s',
                    $spec->getNamespace()->getName(),
                    $spec->getName()->getValue()
                )
            );
        }
    }
}
