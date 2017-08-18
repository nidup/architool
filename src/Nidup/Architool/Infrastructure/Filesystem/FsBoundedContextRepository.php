<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\Model\BoundedContext;
use Nidup\Architool\Domain\BoundedContextRepository;
use phpDocumentor\Reflection\File;
use Symfony\Component\Filesystem\Filesystem;

final class FsBoundedContextRepository implements BoundedContextRepository
{
    private $srcPath;
    private $filesystem;

    public function __construct(Filesystem $filesystem, string $srcPath)
    {
        $this->srcPath = $srcPath;
        $this->filesystem = $filesystem;
    }

    public function add(BoundedContext $context)
    {
        $contextDir = $this->srcPath.DIRECTORY_SEPARATOR.$context->getName();
        $this->filesystem->mkdir($contextDir);
        foreach ($context->getLayers() as $layer) {
            $layerDir = $contextDir.DIRECTORY_SEPARATOR.$layer->getName();
            $this->filesystem->mkdir($layerDir);
        }
    }
}
