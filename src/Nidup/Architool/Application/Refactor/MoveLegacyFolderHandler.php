<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

use Nidup\Architool\Domain\FileStorage\Folder\FolderNamespace;
use Nidup\Architool\Domain\FileStorage\FolderRepository;

final class MoveLegacyFolderHandler
{
    private $repository;

    public function __construct(FolderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(MoveLegacyFolder $command): void
    {
        $from = new FolderNamespace($command->getLegacyNamespace());
        $to = new FolderNamespace($command->getDestinationNamespace());

        $folder = $this->repository->get($from);
        $folder->move($to);
        $this->repository->update($folder);
    }
}
