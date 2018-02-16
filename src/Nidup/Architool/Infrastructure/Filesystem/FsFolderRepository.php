<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\FileStorage\Folder;
use Nidup\Architool\Domain\FileStorage\Folder\FolderNamespace;
use Nidup\Architool\Domain\FileStorage\FolderRepository;

class FsFolderRepository implements FolderRepository
{
    private $mover;
    private $updater;

    public function __construct(FolderMover $mover, FolderReferenceUpdater $updater)
    {
        $this->mover = $mover;
        $this->updater = $updater;
    }

    public function get(FolderNamespace $namespace): Folder
    {
        return new Folder($namespace);
    }

    public function update(Folder $folder)
    {
        if ($folder->hasMoved()) {
            $this->mover->move($folder);
            $this->updater->update($folder);
        } else {
            throw new \LogicException(
                sprintf(
                    'Calling update on a not modified folder %s',
                    $folder->getOriginalNamespace()->getName()
                )
            );
        }
    }
}
