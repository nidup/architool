<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\Model\SpecFile;
use Symfony\Component\Filesystem\Filesystem;

class SpecFileMover
{
    private $projectPath;
    private $filesystem;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->filesystem = new Filesystem();
    }

    public function move(SpecFile $spec)
    {
        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';

        $fileExtension = '.php';
        $fromFile = $srcPath.DIRECTORY_SEPARATOR.$spec->getOriginalNamespace()->getName().DIRECTORY_SEPARATOR.$spec->getName()->getName().$fileExtension;
        $toDir = $srcPath.DIRECTORY_SEPARATOR.$spec->getNewNamespace()->getName();
        $toFile = $toDir.DIRECTORY_SEPARATOR.$spec->getName()->getName().$fileExtension;

        if (!$this->filesystem->exists($toDir)) {
            $this->filesystem->mkdir($toDir);
        }

        $this->filesystem->rename($fromFile, $toFile);
    }
}
