<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\Model\SpecFile;
use Nidup\Architool\Domain\Model\SpecFile\SpecNamespace;
use Nidup\Architool\Domain\Model\SpecFile\SpecName;
use Symfony\Component\Finder\Finder;

class SpecFileReferenceUpdater
{
    private $projectPath;
    private $fileUpdater;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->fileUpdater = new FileUpdater();
    }

    public function update(SpecFile $spec)
    {
        $source = $spec->getOriginalNamespace();
        $destination = $spec->getNewNamespace();
        $className = $spec->getName();

        $this->changeDeclaration($source, $destination, $className);
    }

    private function changeDeclaration(SpecNamespace $source, SpecNamespace $destination, SpecName $className)
    {
        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $destinationPath = $srcPath.DIRECTORY_SEPARATOR.$destination->getName();
        $finder = new Finder();
        $finder->files()
            ->in($destinationPath)
            ->name($className->getName().'.php');

        $sourceNamespacePattern = str_replace('/spec', '', $source->getName());
        $sourceNamespacePattern = "/namespace spec\\\\".str_replace('/', "\\\\", $sourceNamespacePattern).'/';
        $destinationNamespaceDeclaration = str_replace('/spec', '', $destination->getName());
        $destinationNamespaceDeclaration = "namespace spec\\".str_replace('/', "\\", $destinationNamespaceDeclaration);

        foreach ($finder as $file) {
            $this->fileUpdater->updateExactlyOnce($file, $sourceNamespacePattern, $destinationNamespaceDeclaration);
        }
    }
}
