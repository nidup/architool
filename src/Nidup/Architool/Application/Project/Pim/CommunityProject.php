<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\Refactor\CreateBoundedContexts;
use Nidup\Architool\Application\Project\Project;

class CommunityProject implements Project
{
    public function createBoundedContextsCommand() : CreateBoundedContexts
    {
        $contextNames = [
            'Akeneo/Pim/Platform',
            'Akeneo/Pim/CatalogSetup',
            'Akeneo/Pim/Enrichment',
            'Akeneo/Pim/Collaboration',
            'Akeneo/Pim/WeDoNotAgree',
            'Akeneo/Pim/SomewhereElse',
        ];

        return new CreateBoundedContexts($contextNames);
    }

    public function createOrderedSteps(): array
    {
        return [
            new CreatePlatform(),
        ];
    }
}
