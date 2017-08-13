<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\WorkspaceCleaner;
use Nidup\Architool\Infrastructure\Git\GitStasher;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

final class FsWorkspaceCleaner implements WorkspaceCleaner
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
        $pimNamespacePath = $this->workspacePath.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Pim';
        $legacyPimFolders = ['Component', 'Bundle'];

        $finder = new Finder();
        $finder->directories()
            ->in($pimNamespacePath)
            ->depth(0);

        foreach ($finder as $file) {
            if (!in_array($file->getFilename(), $legacyPimFolders)) {
                $this->filesystem->remove($file);
            }
        }

        $stasher = new GitStasher();
        $stasher->stash($this->workspacePath);
    }

}