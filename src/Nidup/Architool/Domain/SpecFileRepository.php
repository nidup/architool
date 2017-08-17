<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\SpecFile;
use Nidup\Architool\Domain\Model\SpecFile\SpecName;
use Nidup\Architool\Domain\Model\SpecFile\SpecNamespace;

class SpecFileRepository
{
    private $mover;
    private $updater;

    public function __construct(SpecFileMover $mover, SpecFileReferenceUpdater $updater)
    {
        $this->mover = $mover;
        $this->updater = $updater;
    }

    public function get(SpecNamespace $namespace, SpecName $name): SpecFile
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
                    $spec->getOriginalNamespace()->getName(),
                    $spec->getName()->getName()
                )
            );
        }
    }
}
