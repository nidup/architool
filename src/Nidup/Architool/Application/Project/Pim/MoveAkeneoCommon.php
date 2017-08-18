<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactor\ReconfigureSpecFolder;
use Nidup\Architool\Application\Refactor\MoveLegacyFolder;

class MoveAkeneoCommon implements Step
{

    public function getDescription(): string
    {
        return 'Move Akeneo components & bundles in Akeneo/Common';
    }

    public function createReworkCodebaseCommands() : array
    {
        $commands = [
            new MoveLegacyFolder(
                'Akeneo/Component',
                'Akeneo/Common/Component',
                'Move Akeneo common components'
            ),
            new ReconfigureSpecFolder(
                'Akeneo/Component',
                'Akeneo/Common/Component',
                'Move Akeneo common components'
            ),

            new MoveLegacyFolder(
                'Akeneo/Bundle',
                'Akeneo/Common/Bundle',
                'Move Akeneo common bundles'
            ),
            new ReconfigureSpecFolder(
                'Akeneo/Bundle',
                'Akeneo/Common/Bundle',
                'Move Akeneo common bundles'
            ),
        ];

        return $commands;
    }
}
