<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\Workspace\WorkspaceCleaner;
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
        $this->cleanAkeneoDirectory();
        $this->cleanPimDirectory();

        $stasher = new GitStasher();
        $stasher->stash($this->workspacePath);
    }

    private function cleanAkeneoDirectory()
    {
        $pimNamespacePath = $this->workspacePath.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Akeneo';
        $legacyPimFolders = ['Component', 'Bundle'];

        $this->cleanComponentAndBundleDirectory($pimNamespacePath, $legacyPimFolders);
    }

    private function cleanPimDirectory()
    {
        $pimNamespacePath = $this->workspacePath.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Pim';
        $legacyPimFolders = ['Component', 'Bundle'];

        $this->cleanComponentAndBundleDirectory($pimNamespacePath, $legacyPimFolders);
    }

    private function cleanComponentAndBundleDirectory(string $namespacePath, array $authorizedFolders)
    {
        $finder = new Finder();
        $finder->directories()
            ->in($namespacePath)
            ->depth(0);

        foreach ($finder as $file) {
            if (!in_array($file->getFilename(), $authorizedFolders)) {
                $this->filesystem->remove($file);
            }
        }
    }
}