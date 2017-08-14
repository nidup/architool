<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\Refactoring\NamespaceExtractor;
use Nidup\Architool\Domain\CodeNamespace;
use Symfony\Component\Filesystem\Filesystem;

final class FsNamespaceExtractor implements NamespaceExtractor
{
    private $projectPath;
    private $filesystem;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->filesystem = new Filesystem();
    }

    public function extract(CodeNamespace $source, CodeNamespace $destination)
    {
        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';

        $from = $srcPath.DIRECTORY_SEPARATOR.$source->getName();
        $to = $srcPath.DIRECTORY_SEPARATOR.$destination->getName();

        $this->filesystem->rename($from, $to, true);
    }
}
