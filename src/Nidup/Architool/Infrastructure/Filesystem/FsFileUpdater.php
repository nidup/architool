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
                    'No namespace declaration "%s" in the file %s',
                    $toReplacePattern,
                    $file->getRealPath()
                )
            );
        }

        file_put_contents($file->getRealPath(), $newContent);
    }
}