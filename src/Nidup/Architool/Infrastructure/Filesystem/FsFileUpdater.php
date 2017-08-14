<?php

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
}
