<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\Model;

use Nidup\Architool\Domain\Model\File\Name;
use Nidup\Architool\Domain\Model\File\Path;

interface File
{
    public function getName(): Name;
    public function getPath(): Path;
    public function getDestinationDirectoryPath(): Path;
    public function getDestinationPath(): Path;
}
