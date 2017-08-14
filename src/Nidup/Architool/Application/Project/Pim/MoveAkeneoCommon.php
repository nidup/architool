<?php

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactoring\ReconfigureSpecNamespace;
use Nidup\Architool\Application\Refactoring\MoveLegacyClass;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespace;

class MoveAkeneoCommon implements Step
{

    public function getDescription(): string
    {
        return 'Move Akeneo components & bundles in Akeneo/Common';
    }

    public function createReworkCodebaseCommands() : array
    {
        $commands = [
            new MoveLegacyNamespace(
                'Akeneo/Component',
                'Akeneo/Common/Component',
                'Move Akeneo common components'
            ),
            new ReconfigureSpecNamespace(
                'Akeneo/Component',
                'Akeneo/Common/Component',
                'Configure Akeneo common specs'
            ),

            new MoveLegacyNamespace(
                'Akeneo/Bundle',
                'Akeneo/Common/Bundle',
                'Move Akeneo common bundles'
            ),
            new ReconfigureSpecNamespace(
                'Akeneo/Bundle',
                'Akeneo/Common/Bundle',
                'Configure Akeneo common specs'
            ),
        ];

        return $commands;
    }
}
