<?php

namespace Nidup\Architool\Application\Project;

use Nidup\Architool\Application\BoundedContext\CreateBoundedContexts;

interface Project
{
    public function createBoundedContextsCommand() : CreateBoundedContexts;

    public function createOrderedSteps() : array;
}
