<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\FileStorage\File;
use Symfony\Component\Filesystem\Filesystem;

class FileMover
{
    private $projectPath;
    private $filesystem;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->filesystem = new Filesystem();
    }

    public function move(File $file)
    {
        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';

        $fromFile = $srcPath.DIRECTORY_SEPARATOR.$file->getPath()->getContent();
        $toDir = $srcPath.DIRECTORY_SEPARATOR.$file->getDestinationDirectoryPath()->getContent();
        $toFile = $srcPath.DIRECTORY_SEPARATOR.$file->getDestinationPath()->getContent();

        if (!$this->filesystem->exists($toDir)) {
            $this->filesystem->mkdir($toDir);
        }

        $this->filesystem->rename($fromFile, $toFile);
    }
}
