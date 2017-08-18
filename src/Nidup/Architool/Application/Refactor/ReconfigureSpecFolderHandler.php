<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

use Nidup\Architool\Domain\Model\Folder;
use Nidup\Architool\Domain\Model\Folder\FolderNamespace;
use Nidup\Architool\Domain\SpecConfigurationFileRepository;

final class ReconfigureSpecFolderHandler
{
    private $repository;

    public function __construct(SpecConfigurationFileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ReconfigureSpecFolder $command): void
    {
        $file = $this->repository->get();
        $folder = new Folder(new FolderNamespace($command->getLegacyNamespace()));
        $folder->move(new FolderNamespace($command->getDestinationNamespace()));
        $file->reconfigure($folder);
        $this->repository->update($file);
    }
}
