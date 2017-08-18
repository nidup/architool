<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\Model\ClassFile;
use Symfony\Component\Filesystem\Filesystem;

class ClassFileMover
{
    private $projectPath;
    private $filesystem;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->filesystem = new Filesystem();
    }

    public function move(ClassFile $class)
    {
        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';

        $fileExtension = '.php';
        $fromFile = $srcPath.DIRECTORY_SEPARATOR.$class->getOriginalNamespace()->getName().DIRECTORY_SEPARATOR.$class->getName()->getName().$fileExtension;
        $toDir = $srcPath.DIRECTORY_SEPARATOR.$class->getNewNamespace()->getName();
        $toFile = $toDir.DIRECTORY_SEPARATOR.$class->getName()->getName().$fileExtension;

        if (!$this->filesystem->exists($toDir)) {
            $this->filesystem->mkdir($toDir);
        }

        $this->filesystem->rename($fromFile, $toFile);
    }
}
