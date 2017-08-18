<?php

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\SpecConfigurationFile;

interface SpecConfigurationFileRepository
{
    public function get(): SpecConfigurationFile;

    public function update(SpecConfigurationFile $file);
}
