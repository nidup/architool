<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\BoundedContext;
use Nidup\Architool\Domain\BoundedContextRepository;
use Symfony\Component\Filesystem\Filesystem;

final class FsBoundedContextRepository implements BoundedContextRepository
{
    private $srcPath;
    private $filesystem;

    public function __construct(string $srcPath)
    {
        $this->srcPath = $srcPath;
        $this->filesystem = new Filesystem();
    }

    public function create(BoundedContext $context)
    {
        $contextDir = $this->srcPath.DIRECTORY_SEPARATOR.$context->getName();
        $this->filesystem->mkdir($contextDir);
        foreach ($context->getLayers() as $layer) {
            $layerDir = $contextDir.DIRECTORY_SEPARATOR.$layer->getName();
            $this->filesystem->mkdir($layerDir);
        }
    }
}
