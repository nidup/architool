<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\NamespaceExtractor;
use Nidup\Architool\Domain\CodeNamespace;
use Symfony\Component\Filesystem\Filesystem;

class FsNamespaceExtractor implements NamespaceExtractor
{
    private $srcPath;
    private $filesystem;

    public function __construct(string $srcPath)
    {
        $this->srcPath = $srcPath;
        $this->filesystem = new Filesystem();
    }

    public function extract(CodeNamespace $source, CodeNamespace $destination)
    {
        $from = $this->srcPath.DIRECTORY_SEPARATOR.$source->getName();
        $to = $this->srcPath.DIRECTORY_SEPARATOR.$destination->getName();

        $this->filesystem->rename($from, $to);
    }
}
