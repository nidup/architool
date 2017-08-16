<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

class FsFileUpdater
{
    public function updateIfPossible(\SplFileInfo $file, string $toReplacePattern, string $replacementValue)
    {
        $content = file_get_contents($file->getRealPath());
        $nbReplacements = 0;
        $newContent = preg_replace(
            $toReplacePattern,
            $replacementValue,
            $content,
            -1,
            $nbReplacements
        );

        if ($nbReplacements > 0) {
            file_put_contents($file->getRealPath(), $newContent);
        }
    }

    public function updateExactlyOnce(\SplFileInfo $file, string $toReplacePattern, string $replacementValue)
    {
        $content = file_get_contents($file->getRealPath());
        $nbReplacements = 0;
        $newContent = preg_replace(
            $toReplacePattern,
            $replacementValue,
            $content,
            -1,
            $nbReplacements
        );

        if ($nbReplacements !== 1) {
            throw new \Exception(
                sprintf(
                    'Expecting to find exactly once the following content to replace "%s" in the file %s',
                    $toReplacePattern,
                    $file->getRealPath()
                )
            );
        }

        file_put_contents($file->getRealPath(), $newContent);
    }

    public function updateAtLeastOnce(\SplFileInfo $file, string $toReplacePattern, string $replacementValue)
    {
        $content = file_get_contents($file->getRealPath());
        $nbReplacements = 0;
        $newContent = preg_replace(
            $toReplacePattern,
            $replacementValue,
            $content,
            -1,
            $nbReplacements
        );

        if ($nbReplacements === 0 ) {
            throw new \Exception(
                sprintf(
                    'Expecting to find at least once the following content to replace "%s" in the file %s',
                    $toReplacePattern,
                    $file->getRealPath()
                )
            );
        }

        file_put_contents($file->getRealPath(), $newContent);
    }

    public function appendContent(\SplFileInfo $file, string $contentToAppend)
    {
        $content = file_get_contents($file->getRealPath());
        $newContent = $content.$contentToAppend;
        file_put_contents($file->getRealPath(), $newContent);
    }

    public function containsContent(\SplFileInfo $file, string $contentToSearch): bool
    {
        $content = file_get_contents($file->getRealPath());
        $matches = [];
        preg_match(
            $contentToSearch,
            $content,
            $matches
        );

        return count($matches) > 0;
    }

    public function insertUseStatementAfterNamespace(\SplFileInfo $file, string $lineToInsert)
    {
        $content = file_get_contents($file->getRealPath());
        $lines = explode("\n", $content);

        $newLines = [];
        foreach ($lines as $line) {
            $newLines[] = $line;
            if (strpos($line, 'namespace ') === 0) {
                $newLines[]= $lineToInsert;
            }
        }

        if (count($newLines) !== (count($lines)+1)) {
            throw new \Exception(sprintf('Use statement "%s" has not been inserted in file "%s"', $lineToInsert, $file->getRealPath()));
        }

        $newContent = implode("\n", $newLines);
        file_put_contents($file->getRealPath(), $newContent);
    }
}
