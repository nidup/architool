<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Project;

use Nidup\Architool\Application\Refactor\CreateBoundedContexts;

interface Project
{
    public function createBoundedContextsCommand() : CreateBoundedContexts;

    public function createOrderedSteps() : array;
}
