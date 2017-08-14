<?php

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\Refactoring\CodeReplacer;
use Nidup\Architool\Domain\ClassName;
use Nidup\Architool\Domain\CodeFragment;
use Nidup\Architool\Domain\CodeNamespace;
use Symfony\Component\Finder\Finder;

class FsCodeReplacer implements CodeReplacer
{
    private $projectPath;
    private $fileUpdater;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->fileUpdater = new FsFileUpdater();
    }

    public function replace(
        CodeNamespace $namespace,
        ClassName $class,
        CodeFragment $legacyCode,
        CodeFragment $replacementCode
    ) {

        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $classPath = $srcPath.DIRECTORY_SEPARATOR.$namespace->getName();
        $finder = new Finder();
        $finder->files()
            ->in($classPath)
            ->name($class->getName().'.php');

        $replacementPattern = $legacyCode->getContent();
        $replacementPattern = str_replace('/', "\/", $replacementPattern);
        $replacementPattern = '/'.$replacementPattern.'/';
        $replacementContent = $replacementCode->getContent();

        foreach ($finder as $file) {
            $this->fileUpdater->updateAtLeastOnce($file, $replacementPattern, $replacementContent);
        }
    }
}
