<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Application\Refactoring\CodeReplacer;
use Nidup\Architool\Domain\CodeFragment;
use Nidup\Architool\Domain\CodeNamespace;
use Nidup\Architool\Domain\Model\File\Name;
use Symfony\Component\Finder\Finder;

class FsCodeReplacer implements CodeReplacer
{
    private $projectPath;
    private $fileUpdater;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
        $this->fileUpdater = new FileUpdater();
    }

    public function replace(
        CodeNamespace $namespace,
        Name $class,
        CodeFragment $legacyCode,
        CodeFragment $replacementCode
    ) {

        $srcPath = $this->projectPath.DIRECTORY_SEPARATOR.'src';
        $classPath = $srcPath.DIRECTORY_SEPARATOR.$namespace->getName();
        $finder = new Finder();
        $finder->files()
            ->in($classPath)
            ->name($class->getValue().'.php');

        $replacementPattern = $legacyCode->getContent();
        $replacementPattern = str_replace('/', "\/", $replacementPattern);
        $replacementPattern = '/'.$replacementPattern.'/';
        $replacementContent = $replacementCode->getContent();

        foreach ($finder as $file) {
            $this->fileUpdater->updateAtLeastOnce($file, $replacementPattern, $replacementContent);
        }
    }
}
