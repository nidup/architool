<?php

namespace Nidup\Architool\Domain\FileStorage;

use Nidup\Architool\Domain\FileStorage\Folder\FolderNamespace;

interface FolderRepository
{
    public function get(FolderNamespace $namespace): Folder;

    public function update(Folder $class);
}