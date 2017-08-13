<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\NamespaceExtractor;
use Nidup\Architool\Application\NamespaceRenamer;
use Nidup\Architool\Domain\CodeNamespace;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FsNamespaceRenamer implements NamespaceRenamer
{
    private $srcPath;

    public function __construct(string $srcPath)
    {
        $this->srcPath = $srcPath;
    }

    public function rename(CodeNamespace $source, CodeNamespace $destination)
    {
        $this->changeDeclaration($source, $destination);
        $this->changeReferences($source, $destination);
    }

    private function changeDeclaration(CodeNamespace $source, CodeNamespace $destination)
    {
        $destinationPath = $this->srcPath.DIRECTORY_SEPARATOR.$destination->getName();
        $finder = new Finder();
        $finder->files()
            ->in($destinationPath)
            ->name('*.php')
            ->notName('*Spec.php')
            ->notName('*Integration.php');

        $sourceNamespacePattern = '/namespace '.str_replace('/', "\\\\", $source->getName()).'/';
        $destinationNamespaceDeclaration = 'namespace '.str_replace('/', "\\", $destination->getName());

        foreach ($finder as $file) {
            $content = file_get_contents($file->getRealPath());
            $nbReplacements = 0;
            $newContent = preg_replace(
                $sourceNamespacePattern,
                $destinationNamespaceDeclaration,
                $content,
                -1,
                $nbReplacements
            );

            if ($nbReplacements !== 1) {
                throw new \Exception(
                    sprintf(
                        'No namespace declaration "%s" in the file %s',
                        $sourceNamespacePattern,
                        $file->getRealPath()
                    )
                );
            }

            file_put_contents($file->getRealPath(), $newContent);
        }
    }

    private function changeReferences(CodeNamespace $source, CodeNamespace $destination)
    {
        $finder = new Finder();
        $finder->files()
            ->in($this->srcPath)
            ->name('*.php');

        $sourceNamespacePattern = '/use '.str_replace('/', "\\\\", $source->getName()).'/';
        $destinationNamespaceUse = 'use '.str_replace('/', "\\", $destination->getName());

        foreach ($finder as $file) {
            $content = file_get_contents($file->getRealPath());
            $nbReplacements = 0;
            $newContent = preg_replace(
                $sourceNamespacePattern,
                $destinationNamespaceUse,
                $content,
                -1,
                $nbReplacements
            );

            file_put_contents($file->getRealPath(), $newContent);
        }
    }
}
