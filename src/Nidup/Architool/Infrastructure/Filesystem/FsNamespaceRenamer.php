<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\NamespaceExtractor;
use Nidup\Architool\Application\NamespaceRenamer;
use Nidup\Architool\Domain\CodeNamespace;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FsNamespaceRenamer implements NamespaceRenamer
{
    private $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function rename(CodeNamespace $source, CodeNamespace $destination)
    {
        $this->changeDeclaration($source, $destination);
        $this->changeClassesReferences($source, $destination);
        $this->changeServicesReferences($source, $destination);
        $this->changeAppKernelBundles($source, $destination);
        $this->changeAppConfiguration($source, $destination);
    }

    private function changeDeclaration(CodeNamespace $source, CodeNamespace $destination)
    {
        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $destinationPath = $srcPath.DIRECTORY_SEPARATOR.$destination->getName();
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

    private function changeClassesReferences(CodeNamespace $source, CodeNamespace $destination)
    {
        $finder = new Finder();

        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $behatPath = $this->projectPath.DIRECTORY_SEPARATOR.'features';
        $testsPath = $this->projectPath.DIRECTORY_SEPARATOR.'tests';

        $finder->files()
            ->in([$srcPath, $behatPath, $testsPath])
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

            if ($nbReplacements > 0) {
                file_put_contents($file->getRealPath(), $newContent);
            }
        }
    }

    private function changeServicesReferences(CodeNamespace $source, CodeNamespace $destination)
    {
        $finder = new Finder();

        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';

        $finder->files()
            ->in([$srcPath])
            ->name('*.yml');

        $sourceNamespacePattern = '/'.str_replace('/', "\\\\", $source->getName()).'/';
        $destinationNamespaceUse = ''.str_replace('/', "\\", $destination->getName());

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

            if ($nbReplacements > 0) {
                file_put_contents($file->getRealPath(), $newContent);
            }
        }
    }

    private function changeAppKernelBundles(CodeNamespace $source, CodeNamespace $destination)
    {
        $finder = new Finder();

        $appPath = $this->projectPath.DIRECTORY_SEPARATOR.'app';

        $finder->files()
            ->in($appPath)
            ->name('AppKernel.php');

        $sourceNamespacePattern = '/new '.str_replace('/', "\\\\", $source->getName()).'/';
        $destinationNamespaceUse = 'new '.str_replace('/', "\\", $destination->getName());

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

    private function changeAppConfiguration(CodeNamespace $source, CodeNamespace $destination)
    {
        $finder = new Finder();

        $appPath = $this->projectPath.DIRECTORY_SEPARATOR.'app/config';

        $finder->files()
            ->in($appPath)
            ->name('*.yml');

        $sourceNamespacePattern = '/'.str_replace('/', "\\\\", $source->getName()).'/';
        $destinationNamespaceUse = ''.str_replace('/', "\\", $destination->getName());

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
