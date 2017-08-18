<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\Refactoring\NamespaceExtractor;
use Nidup\Architool\Domain\Model\Folder;
use Symfony\Component\Filesystem\Filesystem;

class FolderMover
{
    private $projectPath;
    private $filesystem;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->filesystem = new Filesystem();
    }

    public function move(Folder $folder)
    {
        $source = $folder->getOriginalNamespace();
        $destination = $folder->getNewNamespace();

        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';

        $from = $srcPath.DIRECTORY_SEPARATOR.$source->getName();
        $to = $srcPath.DIRECTORY_SEPARATOR.$destination->getName();

        $this->filesystem->rename($from, $to, true);
    }
}
