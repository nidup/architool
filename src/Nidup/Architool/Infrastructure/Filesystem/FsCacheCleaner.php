<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\Workspace\CacheCleaner;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

final class FsCacheCleaner implements CacheCleaner
{
    private $workspacePath;
    private $filesystem;

    public function __construct($workspacePath)
    {
        $this->workspacePath = $workspacePath;
        $this->filesystem = new Filesystem();
    }

    public function clean()
    {
        $cachePath = $this->workspacePath.DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'cache';
        $finder = new Finder();
        $finder->directories()
            ->in($cachePath)
            ->depth(0);

        foreach ($finder as $file) {
            $this->filesystem->remove($file);
        }
    }
}