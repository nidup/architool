<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactor\ConfigureSpecFolder;
use Nidup\Architool\Application\Refactor\ReconfigureSpecFolder;
use Nidup\Architool\Application\Refactor\MoveLegacyFolder;
use Nidup\Architool\Application\Refactoring\ReplaceCodeInClass;

class CreatePlatform implements Step
{

    public function getDescription(): string
    {
        return 'Create platform bounded context';
    }

    public function createReworkCodebaseCommands() : array
    {
        $commands = [

            new MoveLegacyFolder(
                'Akeneo/Bundle/BatchBundle',
                'Akeneo/Pim/Platform/Infrastructure/Bundle/BatchBundle',
                ''
            ),
            new MoveLegacyFolder(
                'Akeneo/Bundle/BatchQueueBundle',
                'Akeneo/Pim/Platform/Infrastructure/Bundle/BatchQueueBundle',
                ''
            ),
            new MoveLegacyFolder(
                'Akeneo/Bundle/BufferBundle',
                'Akeneo/Pim/Platform/Infrastructure/Bundle/BufferBundle',
                ''
            ),
            new MoveLegacyFolder(
                'Akeneo/Component/Batch',
                'Akeneo/Pim/Platform/Domain/Batch',
                ''
            ),
            new MoveLegacyFolder(
                'Akeneo/Component/BatchQueue',
                'Akeneo/Pim/Platform/Domain/BatchQueue',
                ''
            ),
        ];

        return $commands;
    }
}
