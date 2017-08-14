<?php

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\BoundedContexts\CreateBoundedContexts;
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
/*            'Akeneo/Pim/ProductEnrichment/Core',
            'Akeneo/Pim/ProductEnrichment/WebApi',
            'Akeneo/Pim/ProductEnrichment/ImportExport',*/
        ];

        return new CreateBoundedContexts($contextNames);
    }

    public function createOrderedSteps(): array
    {
        return [
            new MoveAkeneoCommon(),
            new CreateProductEnrichment()
            //new OneStep()
        ];
    }

}