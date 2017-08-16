<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Project;

interface Step
{
    public function getDescription() : string;

    public function createReworkCodebaseCommands() : array;
}
