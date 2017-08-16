<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Git;

class GitStasher
{
    public function stash(string $projectPath)
    {
        exec(sprintf("cd %s && ls && git stash", $projectPath));
    }
}
