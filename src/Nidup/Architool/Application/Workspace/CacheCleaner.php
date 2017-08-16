<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Workspace;

interface CacheCleaner
{
    public function clean();
}