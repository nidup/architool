<?php

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\Folder;
use Nidup\Architool\Domain\Model\Folder\FolderNamespace;

interface FolderRepository
{
    public function get(FolderNamespace $namespace): Folder;

    public function update(Folder $class);
}