<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\Refactoring\SpecRenamer;
use Nidup\Architool\Domain\ClassName;
use Nidup\Architool\Domain\CodeNamespace;
use Symfony\Component\Finder\Finder;

final class FsSpecRenamer implements SpecRenamer
{
    private $projectPath;
    private $fileUpdater;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->fileUpdater = new FsFileUpdater();
    }

    public function rename(CodeNamespace $source, CodeNamespace $destination, ClassName $className)
    {
        $this->changeDeclaration($source, $destination, $className);
    }

    private function changeDeclaration(CodeNamespace $source, CodeNamespace $destination, ClassName $className)
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
