<?php

namespace Nidup\Architool\Application;

interface Project
{
    public function createBoundedContextsCommand() : CreateBoundedContexts;

    public function createReworkCodebaseCommands() : array;
}
