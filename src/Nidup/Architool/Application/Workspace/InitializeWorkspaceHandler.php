<?php

namespace Nidup\Architool\Application\Workspace;

use Nidup\Architool\Application\Workspace\InitializeWorkspace;
use Nidup\Architool\Application\Workspace\WorkspaceCleaner;

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