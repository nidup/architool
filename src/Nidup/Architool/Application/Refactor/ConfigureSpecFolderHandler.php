<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

use Nidup\Architool\Domain\FileStorage\Folder;
use Nidup\Architool\Domain\FileStorage\SpecConfigurationFileRepository;

final class ConfigureSpecFolderHandler
{
    private $repository;

    public function __construct(SpecConfigurationFileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ConfigureSpecFolder $command): void
    {
        $file = $this->repository->get();
        $folder = new Folder(new Folder\FolderNamespace($command->getNamespace()));
        $file->configure($folder);
        $this->repository->update($file);
    }
}
