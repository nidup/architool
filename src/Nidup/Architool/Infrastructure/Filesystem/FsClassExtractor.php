<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\Refactoring\ClassExtractor;
use Nidup\Architool\Domain\ClassName;
use Nidup\Architool\Domain\CodeNamespace;
use Symfony\Component\Filesystem\Filesystem;

final class FsClassExtractor implements ClassExtractor
{
    private $projectPath;
    private $filesystem;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->filesystem = new Filesystem();
    }

    public function extract(CodeNamespace $source, CodeNamespace $destination, ClassName $class)
    {
        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';

        $fileExtension = '.php';
        $fromFile = $srcPath.DIRECTORY_SEPARATOR.$source->getName().DIRECTORY_SEPARATOR.$class->getName().$fileExtension;
        $toDir = $srcPath.DIRECTORY_SEPARATOR.$destination->getName();
        $toFile = $toDir.DIRECTORY_SEPARATOR.$class->getName().$fileExtension;

        if (!$this->filesystem->exists($toDir)) {
            $this->filesystem->mkdir($toDir);
        }

        $this->filesystem->rename($fromFile, $toFile);
    }
}
