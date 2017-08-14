<?php

namespace Nidup\Architool\Application\Workspace;

final class FinalizeWorkspaceHandler
{
    private $cleaner;

    public function __construct(CacheCleaner $cleaner)
    {
        $this->cleaner = $cleaner;
    }

    public function handle(FinalizeWorkspace $command): void
    {
        $this->cleaner->clean();
    }
}