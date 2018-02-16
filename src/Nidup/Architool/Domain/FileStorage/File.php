<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\FileStorage;

use Nidup\Architool\Domain\FileStorage\File\Name;
use Nidup\Architool\Domain\FileStorage\File\Path;

interface File
{
    public function getName(): Name;
    public function getPath(): Path;
    public function getDestinationDirectoryPath(): Path;
    public function getDestinationPath(): Path;
}
