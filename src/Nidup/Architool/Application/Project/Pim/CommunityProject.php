<?php

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\BoundedContext\CreateBoundedContexts;
use Nidup\Architool\Application\Project\Project;

class CommunityProject implements Project
{
    public function createBoundedContextsCommand() : CreateBoundedContexts
    {
        $contextNames = [
            'Akeneo/Pim/CatalogSetup',
            'Akeneo/Pim/ProductStructure',
            'Akeneo/Pim/ProductEnrichment',
            'Akeneo/Pim/UserManagement',
        ];

        return new CreateBoundedContexts($contextNames);
    }

    public function createOrderedSteps(): array
    {
        return [
            new MoveAkeneoCommon(),
            new CreateProductEnrichment(),
            new CreateUserManagement()
        ];
    }

}