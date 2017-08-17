<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\SpecFile;

interface SpecFileReferenceUpdater
{
    public function update(SpecFile $spec);
}
