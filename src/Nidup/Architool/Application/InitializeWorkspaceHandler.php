<?php

namespace Nidup\Architool\Application;

final class InitializeWorkspaceHandler
{
    private $cleaner;

    public function __construct(WorkspaceCleaner $cleaner)
    {
        $this->cleaner = $cleaner;
    }

    public function handle(InitializeWorkspace $command): void
    {
        $this->cleaner->clean();
    }
}