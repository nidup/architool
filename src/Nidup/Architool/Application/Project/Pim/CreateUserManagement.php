<?php

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactoring\ReconfigureSpecNamespace;
use Nidup\Architool\Application\Refactoring\MoveLegacyClass;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespace;

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
            new MoveLegacyNamespace(
                'Pim/Component/User',
                'Akeneo/Pim/UserManagement/Domain',
                'Move user component as user management domain (some parts will be extracted later on)'
            ),
        ];

        return $commands;
    }
}
