<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\Model\ClassFile;
use Nidup\Architool\Domain\Model\ClassFile\ClassNamespace;
use Nidup\Architool\Domain\Model\ClassFile\ClassName;
use Symfony\Component\Finder\Finder;

class ClassFileReferenceUpdater
{
    private $projectPath;
    private $fileUpdater;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->fileUpdater = new FileUpdater();
    }

    public function update(ClassFile $class)
    {
        $source = $class->getOriginalNamespace();
        $destination = $class->getNewNamespace();
        $className = $class->getName();

        $this->changeDeclaration($source, $destination, $className);
        $this->changeClassesReferences($source, $destination, $className);
        $this->changeServicesReferences($source, $destination, $className);
        $this->addMissingClassReferencesToExNeighbourClasses($source, $destination, $className);
        $this->addMissingExNeighboursReferencesToTheClass($source, $destination, $className);
        $this->changeAppKernelBundles($source, $destination, $className);
        $this->changeAppConfiguration($source, $destination, $className);
    }

    private function changeDeclaration(ClassNamespace $source, ClassNamespace $destination, ClassName $className)
    {
        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $destinationPath = $srcPath.DIRECTORY_SEPARATOR.$destination->getName();
        $finder = new Finder();
        $finder->files()
            ->in($destinationPath)
            ->name($className->getName().'.php');

        $sourceNamespacePattern = '/namespace '.str_replace('/', "\\\\", $source->getName()).'/';
        $destinationNamespaceDeclaration = 'namespace '.str_replace('/', "\\", $destination->getName());

        foreach ($finder as $file) {
            $this->fileUpdater->updateExactlyOnce($file, $sourceNamespacePattern, $destinationNamespaceDeclaration);
        }
    }

    private function changeClassesReferences(ClassNamespace $source, ClassNamespace $destination, ClassName $className)
    {
        $finder = new Finder();

        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $behatPath = $this->projectPath.DIRECTORY_SEPARATOR.'features';
        $testsPath = $this->projectPath.DIRECTORY_SEPARATOR.'tests';

        $finder->files()
            ->in([$srcPath, $behatPath, $testsPath])
            ->name('*.php');

        $sourceNamespacePattern = '/'.str_replace('/', "\\\\", $source->getName()."\\\\".$className->getName()).'/';
        $destinationNamespace = ''.str_replace('/', "\\", $destination->getName()."\\".$className->getName());

        foreach ($finder as $file) {
            $this->fileUpdater->updateIfPossible($file, $sourceNamespacePattern, $destinationNamespace);
        }
    }

    private function addMissingClassReferencesToExNeighbourClasses(ClassNamespace $source, ClassNamespace $destination, ClassName $className)
    {
        $finder = new Finder();

        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $oldNeighbourPath = $srcPath.DIRECTORY_SEPARATOR.$source->getName();

        $finder->files()
            ->in($oldNeighbourPath)
            ->depth(0)
            ->name('*.php');

        $classSearchPattern = '/'.$className->getName().'/';
        $missingMovedClassUse = 'use '.str_replace('/', "\\", $destination->getName()."\\".$className->getName()).';';

        foreach ($finder as $file) {
            if ($this->fileUpdater->containsContent($file, $classSearchPattern)) {
                $this->fileUpdater->insertUseStatementAfterNamespace($file, $missingMovedClassUse);
            }
        }
    }

    private function addMissingExNeighboursReferencesToTheClass(ClassNamespace $source, ClassNamespace $destination, ClassName $className)
    {
        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $newClassPath = $srcPath.DIRECTORY_SEPARATOR.$destination->getName();
        $finder = new Finder();
        $finder->files()
            ->in($newClassPath)
            ->depth(0)
            ->name($className->getName().'.php');

        $movedClassFile = null;
        foreach ($finder as $file) {
            $movedClassFile = $file;
            break;
        }

        $finder = new Finder();

        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $oldNeighbourPath = $srcPath.DIRECTORY_SEPARATOR.$source->getName();

        $finder->files()
            ->in($oldNeighbourPath)
            ->depth(0)
            ->name('*.php');

        foreach ($finder as $file) {
            $neighbourClassName = str_replace('.php','', $file->getFilename());
            $missingNeighbougClassUse = 'use '.str_replace('/', "\\", $source->getName()."\\".$neighbourClassName).';';

            $neighbourClassNameTypehintPattern = '/'.$neighbourClassName.' /';
            $neighbourClassNameExtendsSeveralPattern = '/ '.$neighbourClassName.',/';
            $neighbourClassNameExtendsOncePattern = '/ '.$neighbourClassName.'/';

            if ($this->fileUpdater->containsContent($movedClassFile, $neighbourClassNameTypehintPattern)) {
                $this->fileUpdater->insertUseStatementAfterNamespace($movedClassFile, $missingNeighbougClassUse);
            } else if ($this->fileUpdater->containsContent($movedClassFile, $neighbourClassNameExtendsSeveralPattern)) {
                $this->fileUpdater->insertUseStatementAfterNamespace($movedClassFile, $missingNeighbougClassUse);
            } else if ($this->fileUpdater->containsContent($movedClassFile, $neighbourClassNameExtendsOncePattern)) {
                $this->fileUpdater->insertUseStatementAfterNamespace($movedClassFile, $missingNeighbougClassUse);
            }
        }
    }

    private function changeServicesReferences(ClassNamespace $source, ClassNamespace $destination, ClassName $className)
    {
        $finder = new Finder();

        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';

        $finder->files()
            ->in([$srcPath])
            ->name('*.yml');

        $sourceNamespacePattern = '/'.str_replace('/', "\\\\", $source->getName()."\\\\".$className->getName()).'/';
        $destinationNamespace = ''.str_replace('/', "\\", $destination->getName()."\\".$className->getName());

        foreach ($finder as $file) {
            $this->fileUpdater->updateIfPossible($file, $sourceNamespacePattern, $destinationNamespace);
        }
    }

    private function changeAppKernelBundles(ClassNamespace $source, ClassNamespace $destination, ClassName $className)
    {
        $finder = new Finder();

        $appPath = $this->projectPath.DIRECTORY_SEPARATOR.'app';

        $finder->files()
            ->in($appPath)
            ->name('AppKernel.php');

        $sourceNamespacePattern = '/new '.str_replace('/', "\\\\", $source->getName()."\\\\".$className->getName()).'/';
        $destinationNamespace = 'new '.str_replace('/', "\\", $destination->getName()."\\".$className->getName());

        foreach ($finder as $file) {
            $this->fileUpdater->updateIfPossible($file, $sourceNamespacePattern, $destinationNamespace);
        }
    }

    private function changeAppConfiguration(ClassNamespace $source, ClassNamespace $destination, ClassName $className)
    {
        $finder = new Finder();

        $appPath = $this->projectPath.DIRECTORY_SEPARATOR.'app/config';

        $finder->files()
            ->in($appPath)
            ->name('*.yml');

        $sourceNamespacePattern = '/'.str_replace('/', "\\\\", $source->getName()."\\\\".$className->getName()).'/';
        $destinationNamespace = ''.str_replace('/', "\\", $destination->getName()."\\".$className->getName());

        foreach ($finder as $file) {
            $this->fileUpdater->updateIfPossible($file, $sourceNamespacePattern, $destinationNamespace);
        }
    }
}
