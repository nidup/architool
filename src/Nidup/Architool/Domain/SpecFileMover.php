<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\SpecFile;

interface SpecFileMover
{
    public function move(SpecFile $specFile);
}