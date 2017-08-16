<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Workspace;

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