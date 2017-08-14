<?php

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\BoundedContexts\CreateBoundedContexts;
use Nidup\Architool\Application\Project\Project;

class CommunityProject implements Project
{
    public function createBoundedContextsCommand() : CreateBoundedContexts
    {
        $contextNames = [
            'UserManagement',
            'CatalogSetup',
            'ProductStructure',
            'ProductEnrichment/Core',
            'ProductEnrichment/WebApi',
            'ProductEnrichment/ImportExport',
        ];

        return new CreateBoundedContexts($contextNames);
    }

    public function createOrderedSteps(): array
    {
        return [
            new OneStep()
        ];
    }

}