<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactor\MoveLegacyFolder;

class CreateUserManagement implements Step
{

    public function getDescription(): string
    {
        return 'Create user management bounded context';
    }

    public function createReworkCodebaseCommands() : array
    {
        $commands = [
            // Domain
            new MoveLegacyFolder(
                'Pim/Component/User',
                'Akeneo/Pim/UserManagement/Domain',
                'Move user component as user management domain (some parts will be extracted later on)'
            ),
        ];

        return $commands;
    }
}
