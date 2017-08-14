<?php

namespace Nidup\Architool\Application\Project;

interface Step
{
    public function getName() : string;

    public function createReworkCodebaseCommands() : array;
}
