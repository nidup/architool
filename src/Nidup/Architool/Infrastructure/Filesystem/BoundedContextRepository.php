<?php

namespace Nidup\Architool\Infrastructure\FileSystem;

use Nidup\Architool\Domain\BoundedContext;
use Symfony\Component\Filesystem\Filesystem;

final class BoundedContextRepository implements \Nidup\Architool\Domain\BoundedContextRepository
{
    private $srcPath;

    public function __construct(string $srcPath)
    {
        $this->srcPath = $srcPath;
    }

    public function create(BoundedContext $context)
    {
        $fs = new Filesystem();
        $contextDir = $this->srcPath.DIRECTORY_SEPARATOR.$context->getName();
        $fs->mkdir($contextDir);
        foreach ($context->getLayers() as $layer) {
            $layerDir = $contextDir.DIRECTORY_SEPARATOR.$layer->getName();
            $fs->mkdir($layerDir);
        }
    }
}
