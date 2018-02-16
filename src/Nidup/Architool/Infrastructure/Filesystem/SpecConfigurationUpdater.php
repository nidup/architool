<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\FileStorage\Folder;
use Symfony\Component\Finder\Finder;

final class SpecConfigurationUpdater
{
    private $projectPath;
    private $fileUpdater;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->fileUpdater = new FileUpdater();
    }

    public function reconfigure(Folder $folder)
    {
        $fromNamespace = $folder->getOriginalNamespace();
        $toNamespace = $folder->getNewNamespace();

        $sourceNamespacePattern = '/'.str_replace('/', "\\\\", $fromNamespace->getName()).'/';
        $destinationNamespace = ''.str_replace('/', "\\", $toNamespace->getName());

        $sourcePathPattern = '/src\/'.str_replace('/', "\/", $fromNamespace->getName()).'/';
        $destinationPath = 'src/'.$toNamespace->getName();

        $this->haveToConfigureMainFile(
            $sourceNamespacePattern,
            $destinationNamespace,
            $sourcePathPattern,
            $destinationPath
        );

        $this->mayConfigureExtraFiles(
            $sourceNamespacePattern,
            $destinationNamespace,
            $sourcePathPattern,
            $destinationPath
        );
    }

    public function configure(Folder $folder)
    {
        $newNamespace = $folder->getOriginalNamespace();

        $namespace = ''.str_replace('/', "\\", $newNamespace->getName());
        $namespacePath = 'src/'.$newNamespace->getName();
        $configName = str_replace('/', '', $newNamespace->getName());

        $newConfiguration = sprintf(
            "\n    %s:\n        namespace: %s\n        psr4_prefix: %s\n        spec_path: %s\n        src_path: %s",
            $configName,
            $namespace,
            $namespace,
            $namespacePath,
            $namespacePath
        );

        $finder = new Finder();
        $finder->files()
            ->in($this->projectPath)
            ->name('phpspec.yml*')
            ->depth(0);

        foreach ($finder as $file) {
            $this->fileUpdater->appendContent($file, $newConfiguration);
        }
    }

    private function haveToConfigureMainFile(
        string $sourceNamespacePattern,
        string $destinationNamespace,
        string $sourcePathPattern,
        string $destinationPath
    ) {
        $finder = new Finder();
        $finder->files()
            ->in($this->projectPath)
            ->name('phpspec.yml*')
            ->depth(0);

        foreach ($finder as $file) {
            $this->fileUpdater->updateAtLeastOnce($file, $sourceNamespacePattern, $destinationNamespace);
            $this->fileUpdater->updateAtLeastOnce($file, $sourcePathPattern, $destinationPath);
        }
    }

    private function mayConfigureExtraFiles(
        string $sourceNamespacePattern,
        string $destinationNamespace,
        string $sourcePathPattern,
        string $destinationPath
    ) {
        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $finder = new Finder();
        $finder->files()
            ->in($srcPath)
            ->name('phpspec.yml*');

        foreach ($finder as $file) {
            $this->fileUpdater->updateIfPossible($file, $sourceNamespacePattern, $destinationNamespace);
            $this->fileUpdater->updateIfPossible($file, $sourcePathPattern, $destinationPath);
        }
    }
}
