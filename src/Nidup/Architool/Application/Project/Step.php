<?php

namespace Nidup\Architool\Application\Project;

interface Step
{
    public function getDescription() : string;

    public function createReworkCodebaseCommands() : array;
}
