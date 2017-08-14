<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\Refactoring\SpecNamespaceConfigurator;
use Nidup\Architool\Domain\CodeNamespace;
use Symfony\Component\Finder\Finder;

final class FsSpecNamespaceConfigurator implements SpecNamespaceConfigurator
{
    private $projectPath;
    private $fileUpdater;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->fileUpdater = new FsFileUpdater();
    }

    public function configure(CodeNamespace $source, CodeNamespace $destination)
    {
        $finder = new Finder();
        $finder->files()
            ->in($this->projectPath)
            ->name('phpspec.yml*');

        $sourceNamespacePattern = '/'.str_replace('/', "\\\\", $source->getName()).'/';
        $destinationNamespace = ''.str_replace('/', "\\", $destination->getName());

        $sourcePathPattern = '/src\/'.str_replace('/', "\/", $source->getName()).'/';
        $destinationPath = 'src/'.$destination->getName();

        foreach ($finder as $file) {
            $this->fileUpdater->updateIfPossible($file, $sourceNamespacePattern, $destinationNamespace);
            $this->fileUpdater->updateIfPossible($file, $sourcePathPattern, $destinationPath);
        }
    }

}
